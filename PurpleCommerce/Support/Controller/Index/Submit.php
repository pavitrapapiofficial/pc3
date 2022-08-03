<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PurpleCommerce\Support\Controller\Index;

use \Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session;

class Submit extends Action
{
    private $dataPersistor;
    /**
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */

    protected $context;
    private $fileSystem;
    protected $fileUploader;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        Filesystem $fileSystem,
        UploaderFactory $fileUploader,
        \PurpleCommerce\Support\Block\Index $PIblock,
        \PurpleCommerce\Support\Helper\Data $helper,
        \PurpleCommerce\Support\Model\Support $modlefactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        ResultFactory $resultFactory,
        Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
        $this->mediaDirectory = $fileSystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->fileSystem          = $fileSystem;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->piblock = $PIblock;
        $this->_helper=$helper;
        $this->customerSession = $customerSession;
        $this->resultFactory      = $resultFactory;
        $this->_messageManager = $messageManager;
        $this->model = $modlefactory;
    }

    public function execute()
    {

        $post = $this->getRequest()->getPostValue();
        
        if ($post['form-type']=='chatbox') {
            $query=[];
            
            if ($post['sub']) {
                $query['subject']=$post['sub'];
                if (strtolower($post['sub'])=='technical') {
                    $query['severity'] = 1;
                } elseif (strtolower($post['sub'])=='installation') {
                    $query['severity'] = 1;
                } elseif (strtolower($post['sub'])=='billing') {
                    $query['severity'] = 0;
                } elseif (strtolower($post['sub'])=='others') {
                    $query['severity'] = 2;
                }
            }
            if ($post['customer_query']) {
                $query['customer_query']=$post['customer_query'];
            }
            if ($post['customer-id']) {
                $query['customer_id']=$post['customer-id'];
            }
            $query['ticket_id']=$post['ticket_id'];
            $query['status']=$post['status'];
            try {
                $folderName = 'pc_helpdesk_attachemt';
                // echo '-->try';
                $file = $this->getRequest()->getFiles('attachment');
                $fileName = ($file && array_key_exists('name', $file)) ? $file['name'] : null;
                
                if ($file && $fileName) {
                    // echo '-->if';
                    $target = $this->mediaDirectory->getAbsolutePath($folderName);
                    
                    /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                    $uploader = $this->fileUploader->create(['fileId' => 'attachment']);
                    
                    // set allowed file extensions
                    $uploader->setAllowedExtensions(['jpg', 'pdf', 'doc', 'png', 'zip']);
                    
                    // allow folder creation
                    $uploader->setAllowCreateFolders(true);
                    
                    // rename file name if already exists
                    $uploader->setAllowRenameFiles(true);
                    
                    // upload file in the specified folder
                    $result = $uploader->save($target);
                    
                    // echo '<pre>'; print_r($result);// exit;
                    
                    if ($result['file']) {
                        $this->_messageManager->addSuccess(__('File has been successfully uploaded.'));
                    }
                    // die;
                    // $post['attachment']= '/pub/media/pc_helpdesk_attachemt/' . $uploader->getUploadedFileName();
                    $query['attachment']= 'media/pc_helpdesk_attachemt/' . $uploader->getUploadedFileName();
                }
            } catch (\Exception $e) {
                $this->_messageManager->addError($e->getMessage());
            }
            $query['created_at']=date("d-m-Y H:i:s");
            $query['updated_at']=date("d-m-Y H:i:s");
            
            // die;
            $this->piblock->InsertCustomerChat($query);
            $this->seneEmails($post, $query,'cb');
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            // Your code
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        } elseif ($post['form-type']=='actionform') {
            $query=[];
            $msg=$this->piblock->updateTicket($post['status'], $post['ticket_id']);
            $tkey = $this->piblock->getTicketkeyById($post['ticket_id']);
            if($post['status']==2){
                $status=' Closed';
            }else{
                $status=' Re-Opened';
            }
            $this->statusUpdateEmails($tkey, $status, $post['customer-email']);
            $this->piblock->setData('customer_info', $msg);
            $this->customerSession->setSuccessMsg($msg);
            // $this->_messageManager->addSuccessMessage(__($msg));
            $this->_redirect('*/*/index');
        } else {
            // echo "<pre>";
            // print_r($post);
            $query=[];
            if ($post['customer']) {
                $query['customer_name']=$post['customer'];
            }
            if ($post['ticket-key']) {
                $query['ticket_key']=$post['ticket-key'];
            }
            if ($post['customer-email']) {
                $query['email']=$post['customer-email'];
                $query['customer_email']=$post['customer-email'];
            }
            if ($post['sub']) {
                $query['subject']=$post['sub'];
                $query['sub']=$post['sub'];
                if (strtolower($post['sub'])=='technical') {
                    $query['severity'] = 1;
                } elseif (strtolower($post['sub'])=='installation') {
                    $query['severity'] = 1;
                } elseif (strtolower($post['sub'])=='billing') {
                    $query['severity'] = 0;
                } elseif (strtolower($post['sub'])=='others') {
                    $query['severity'] = 2;
                }
            }
            if ($post['query']) {
                $query['customer_query']=$post['query'];
            }
            if ($post['customer-id']) {
                $query['customer_id']=$post['customer-id'];
            }
            $query['status']=$post['status'];
            $query['attachment']='';
            $query['created_at']=date("d-m-Y H:i:s");
            $query['updated_at']=date("d-m-Y H:i:s");
            // die;
            //--------->db insertion
            $msg = 'Request can not be processed at this time. Please try again later';
            if ($query) {
                $msg='';
                $this->model->setData($query);
                $insertedId = $this->model->getId();
                if ($this->model->Save()) {
                    $this->piblock->InsertCustomerResponse($query);
                    $this->seneEmails($query, $query, 'nw');
                    $msg = 'Ticket raised successfully. Your Ticket Key is- '.$post['ticket-key'];
                } else {
                    $msg = 'Request can not be processed at this time. Please try again later';
                }
            }
            /** @var Page $page */
            $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            // echo '<pre>';
            // print_r($result);
            // die;
            /** @var Template $block */
            // $block = $page->getLayout()->getBlock('my_tab');
            $this->piblock->setData('customer_info', $msg);
            $this->customerSession->setSuccessMsg($msg);
            $this->_redirect('*/*/index');
        }
        
    }

    public function statusUpdateEmails($tkey, $status, $email)
    {
        
        $txt = '<table>';
        $txt.='<tr><td>Your Ticket '.$tkey.' has been successfully '.$status.'</td></tr>';
                
        $txt.='</table>';
        
        $tmpl=1;
        
        $message=$txt;
        $adminSubject = 'Ticket key - #'.$tkey.' has been successully'. $status;
        
        $fromEmail= $this->scopeConfig->getValue(
            'trans_email/ident_support/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        // $custfrom = 'info@theshopindia.com';

            $templateVars = [
                    'store' => 1,
                    'customer_name' => 'User',
                    'subject' => $adminSubject,
                    'message'   => $message
                ];
            $from = ['email' => $fromEmail, 'name' => 'Support'];
        // $customerFrom = ['email' => $custfrom, 'name' => "The Shop India"];
            // $to=$this->scopeConfig->getValue(
            //     'trans_email/ident_sales/email',
            //     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            // );

        // if(!empty($this->piblock->getAdminEmail())){
        //     $to = $this->piblock->getAdminEmail();
        // }
        // $recipients=[]
            
            $emails=[];
        
        //  $primary_emials;
        // print_r()
            $to=$emails;
            $this->inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => 1
            ];
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($templateVars);
            
            $transport = $this->_transportBuilder->setTemplateIdentifier('piemail_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($from)
            ->addTo($email)
            ->getTransport();
            $transport->sendMessage();
            
            $this->inlineTranslation->resume();
    }

    public function seneEmails($post, $query, $type='')
    {
        $primary_emials = $this->_helper->getPrimaryEmails();
        $esc_emials = $this->_helper->getEscEmails();
        $esc_days = $this->_helper->getEscDays();
        if (isset($post['ticket_id'])) {
            $tkey = $this->piblock->getTicketkeyById($post['ticket_id']);
        } else {
            $tkey = $post['ticket_key'];
        }
        
        if ($post['status']==0) {
            $status = '<spna style="color:#C31919">Open</span>';
        } elseif ($post['status']==1) {
            $status = '<spna style="color:#ea8600">In Process</span>';
        } else {
            $status = '<spna style="color:#0DA513">Closed</span>';
        }

        if ($post['severity']==0) {
            $severity = '<spna style="color:#C31919">Medium</span>';
        } elseif ($post['severity']==1) {
            $severity = '<spna style="color:#C31919">High</span>';
        } else {
            $severity = '<spna style="color:#C31919">Low</span>';
        }
        
        $txt = '<table>';
        if ($severity) {
            $txt.='<tr><td><strong>Severity</strong>:'.$severity.'</td></tr>';
        }
        if ($status) {
            $txt.='<tr><td><strong>Ticket Status</strong>:'.$status.'</td></tr>';
        }
        if ($tkey) {
            $txt.='<tr><td><strong>Ticket Key</strong>:'.$tkey.'</td></tr>';
        }
        if ($post['customer_name']) {
            $txt.='<tr><td><strong>Customer Name</strong>:'.$post['customer_name'].'</td></tr>';
        }
        if ($post['customer_email']) {
            $txt.='<tr><td><strong>Customer Email</strong>:'.$post['customer_email'].'</td></tr>';
        }
        if ($post['sub']) {
            $txt.='<tr><td><strong>Subject</strong>:'.$post['sub'].'</td></tr>';
        }
        
        if ($post['customer_query']) {
            $txt.='<tr><td><strong>Message</strong>:'.$post['customer_query'].'</td></tr>';
        }
        if ($query['created_at']) {
            $txt.='<tr><td><strong>Created at</strong>:'.$query['created_at'].'</td></tr>';
        }

        $txt.='</table>';
        if($type!='' && $type=='nw'){
            $this->ticketRaiseEmails($post, $query);
        } else if($type!='' && $type=='cb'){
            $this->chatEmails($post, $query);
        }
        $tmpl=1;
        
        $message=$txt;
        $adminSubject = 'New Message from Ticket key - #'.$tkey;
        
        $fromEmail= $this->scopeConfig->getValue(
            'trans_email/ident_support/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        // $custfrom = 'info@theshopindia.com';

            $templateVars = [
                    'store' => 1,
                    'customer_name' => $post['customer_name'],
                    'subject' => $adminSubject,
                    'message'   => $message
                ];
            $from = ['email' => $fromEmail, 'name' => $post['customer_name']];
        // $customerFrom = ['email' => $custfrom, 'name' => "The Shop India"];
            // $to=$this->scopeConfig->getValue(
            //     'trans_email/ident_sales/email',
            //     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            // );

        // if(!empty($this->piblock->getAdminEmail())){
        //     $to = $this->piblock->getAdminEmail();
        // }
        // $recipients=[]
            $recipients=[];
            if( $primary_emials!='')
                $recipients = explode(',', $primary_emials);
            $emails=[];
        
        //  $primary_emials;
        // print_r()
            $to=$emails;
            $this->inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => 1
            ];
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($templateVars);
            
            if(!empty($recipients))
            {
                foreach ($recipients as $v) {
                    $transport = $this->_transportBuilder->setTemplateIdentifier('piemail_template')
                    ->setTemplateOptions($templateOptions)
                    ->setTemplateVars(['data' => $postObject])
                    ->setFrom($from)
                    ->addTo($v)
                    ->getTransport();
                    $transport->sendMessage();
                }
            }
            
            $this->inlineTranslation->resume();
    }

    public function chatEmails($post, $query)
    {
        $primary_emials = $this->_helper->getPrimaryEmails();
        $esc_emials = $this->_helper->getEscEmails();
        $esc_days = $this->_helper->getEscDays();
        if (isset($post['ticket_id'])) {
            $tkey = $this->piblock->getTicketkeyById($post['ticket_id']);
        } else {
            $tkey = $post['ticket_key'];
        }
        
        if ($post['status']==0) {
            $status = '<spna style="color:#C31919">Open</span>';
        } elseif ($post['status']==1) {
            $status = '<spna style="color:#ea8600">In Process</span>';
        } else {
            $status = '<spna style="color:#0DA513">Closed</span>';
        }

        if ($post['severity']==0) {
            $severity = '<spna style="color:#C31919">Medium</span>';
        } elseif ($post['severity']==1) {
            $severity = '<spna style="color:#C31919">High</span>';
        } else {
            $severity = '<spna style="color:#C31919">Low</span>';
        }
        
        $txt = '<table>';
        if ($severity) {
            $txt.='<tr><td><strong>Severity</strong>:'.$severity.'</td></tr>';
        }
        if ($status) {
            $txt.='<tr><td><strong>Ticket Status</strong>:'.$status.'</td></tr>';
        }
        if ($tkey) {
            $txt.='<tr><td><strong>Ticket Key</strong>:'.$tkey.'</td></tr>';
        }
        
        if ($post['sub']) {
            $txt.='<tr><td><strong>Subject</strong>:'.$post['sub'].'</td></tr>';
        }
        
        if ($post['customer_query']) {
            $txt.='<tr><td><strong>Message</strong>:'.$post['customer_query'].'</td></tr>';
        }
        if ($query['created_at']) {
            $txt.='<tr><td><strong>Created at</strong>:'.$query['created_at'].'</td></tr>';
        }

        $txt.='</table>';
        
        $tmpl=1;
        
        $message=$txt;
        $adminSubject = 'You Replied Ticket key - #'.$tkey;
        
        $fromEmail= $this->scopeConfig->getValue(
            'trans_email/ident_support/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        // $custfrom = 'info@theshopindia.com';

            $templateVars = [
                    'store' => 1,
                    'customer_name' => $post['customer_name'],
                    'subject' => $adminSubject,
                    'message'   => $message
                ];
            $from = ['email' => $fromEmail, 'name' => 'Support'];
        // $customerFrom = ['email' => $custfrom, 'name' => "The Shop India"];
            // $to=$this->scopeConfig->getValue(
            //     'trans_email/ident_sales/email',
            //     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            // );

        // if(!empty($this->piblock->getAdminEmail())){
        //     $to = $this->piblock->getAdminEmail();
        // }
        // $recipients=[]
            
            $emails=[];
        
        //  $primary_emials;
        // print_r()
            $to=$emails;
            $this->inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => 1
            ];
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($templateVars);
            $transport = $this->_transportBuilder->setTemplateIdentifier('piemail_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($from)
            ->addTo($post['customer_email'])
            ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
    }

    public function ticketRaiseEmails($post, $query)
    {
        $primary_emials = $this->_helper->getPrimaryEmails();
        $esc_emials = $this->_helper->getEscEmails();
        $esc_days = $this->_helper->getEscDays();
        if (isset($post['ticket_id'])) {
            $tkey = $this->piblock->getTicketkeyById($post['ticket_id']);
        } else {
            $tkey = $post['ticket_key'];
        }
        
        if ($post['status']==0) {
            $status = '<spna style="color:#C31919">Open</span>';
        } elseif ($post['status']==1) {
            $status = '<spna style="color:#ea8600">In Process</span>';
        } else {
            $status = '<spna style="color:#0DA513">Closed</span>';
        }

        if ($post['severity']==0) {
            $severity = '<spna style="color:#C31919">Medium</span>';
        } elseif ($post['severity']==1) {
            $severity = '<spna style="color:#C31919">High</span>';
        } else {
            $severity = '<spna style="color:#C31919">Low</span>';
        }
        
        $txt = '<table>';
        if ($severity) {
            $txt.='<tr><td><strong>Severity</strong>:'.$severity.'</td></tr>';
        }
        if ($status) {
            $txt.='<tr><td><strong>Ticket Status</strong>:'.$status.'</td></tr>';
        }
        if ($tkey) {
            $txt.='<tr><td><strong>Ticket Key</strong>:'.$tkey.'</td></tr>';
        }
        if ($post['sub']) {
            $txt.='<tr><td><strong>Subject</strong>:'.$post['sub'].'</td></tr>';
        }
        
        if ($post['customer_query']) {
            $txt.='<tr><td><strong>Message</strong>:'.$post['customer_query'].'</td></tr>';
        }
        if ($query['created_at']) {
            $txt.='<tr><td><strong>Created at</strong>:'.$query['created_at'].'</td></tr>';
        }

        $txt.='</table>';
        
        $tmpl=1;
        
        $message=$txt;
        $adminSubject = 'Ticket Raised Successfully- key - #'.$tkey;
        
        $fromEmail= $this->scopeConfig->getValue(
            'trans_email/ident_support/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        // $custfrom = 'info@theshopindia.com';

            $templateVars = [
                    'store' => 1,
                    'customer_name' => $post['customer_name'],
                    'subject' => $adminSubject,
                    'message'   => $message
                ];
            $from = ['email' => $fromEmail, 'name' => 'Support'];
        // $customerFrom = ['email' => $custfrom, 'name' => "The Shop India"];
            // $to=$this->scopeConfig->getValue(
            //     'trans_email/ident_sales/email',
            //     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            // );

        // if(!empty($this->piblock->getAdminEmail())){
        //     $to = $this->piblock->getAdminEmail();
        // }
        // $recipients=[]
            $recipients=[];
            if( $primary_emials!='')
                $recipients = explode(',', $primary_emials);
            $emails=[];
        
        //  $primary_emials;
        // print_r()
            $to=$emails;
            $this->inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => 1
            ];
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($templateVars);
            
            $transport = $this->_transportBuilder->setTemplateIdentifier('piemail_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($from)
            ->addTo($post['customer_email'])
            ->getTransport();
            $transport->sendMessage();
            
            $this->inlineTranslation->resume();
    }
}
