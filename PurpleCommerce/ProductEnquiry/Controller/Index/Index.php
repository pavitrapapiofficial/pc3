<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PurpleCommerce\ProductEnquiry\Controller\Index;

use \Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Request\DataPersistorInterface;

class Index extends Action
{
    private $dataPersistor;
    /**
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */

    protected $context;
    private $fileUploaderFactory;
    private $fileSystem;

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
        \PurpleCommerce\ProductEnquiry\Block\Index $PIblock,
        \PurpleCommerce\ProductEnquiry\Model\ProductEnquiry $modlefactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->fileSystem          = $fileSystem;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->piblock = $PIblock;
        $this->_messageManager = $messageManager;
        $this->model = $modlefactory;
    }

    public function execute()
    {

        $post = $this->getRequest()->getPostValue();
        // echo "<pre>";
        // print_r($post);
        // echo "</pre>";
        // die;
        $query=[];
        if ($post['fullname']) {
            $query['name']=$post['fullname'];
        }
        if ($post['phone']) {
            $query['telephone']=$post['phone'];
        }
        if ($post['email']) {
            $query['email']=$post['email'];
        }
        if ($post['subject']) {
            $query['subject']=$post['subject'];
        }
        if ($post['message']) {
            $query['message']=$post['message'];
        }
        $query['status']=0;
        $query['created_at']=date("Y-m-d H:i:s");
        // die;
        //--------->db insertion
        if ($query) {
            
            $this->model->setData($query);
            $saved=$this->model->Save();
            
        }
        //--------->
        //--------->email data creation
       
        if ($this->piblock->sendEmailToAdmin()) {
            $txt = '<table>';
            if ($post['psku']) {
                $txt.='<tr><td><strong>Product SKU</strong>:'.$post['psku'].'</td></tr>';
            }
            if ($post['pname']) {
                $txt.='<tr><td><strong>Product Name</strong>:'.$post['pname'].'</td></tr>';
            }
            if ($post['fullname']) {
                $txt.='<tr><td><strong>Name</strong>:'.$post['fullname'].'</td></tr>';
            }
            if ($post['email']) {
                $txt.='<tr><td><strong>Email</strong>:'.$post['email'].'</td></tr>';
            }
            if ($post['phone']) {
                $txt.='<tr><td><strong>Phone</strong>:'.$post['phone'].'</td></tr>';
            }
            if ($post['subject']) {
                $txt.='<tr><td><strong>Subject</strong>:'.$post['subject'].'</td></tr>';
            }
            if (isset($post['message'])) {
                $txt.='<tr><td><strong>Message</strong>:'.$post['message'].'</td></tr>';
            }
            if (isset($post['customone'])) {
                $txt.='<tr><td><strong>Custom Filed 1</strong>:'.$post['customtwo'].'</td></tr>';
            }
            if (isset($post['customtwo'])) {
                $txt.='<tr><td><strong>Custom Filed 2</strong>:'.$post['customtwo'].'</td></tr>';
            }
            if (isset($post['customthree'])) {
                $txt.='<tr><td><strong>Custom Filed 3</strong>:'.$post['customthree'].'</td></tr>';
            }
            if (isset($post['customfour'])) {
                $txt.='<tr><td><strong>Custom Filed 4</strong>:'.$post['customfour'].'</td></tr>';
            }
            
            $txt.='</table>';
            
            $tmpl=1;
            
            $message=$txt;
            $adminSubject = 'Product Enquiry for Sku: '.$post['psku'];
            
            $fromEmail= $post['email'];
            $custfrom = 'info@theshopindia.com';
    
             $templateVars = [
                        'store' => 1,
                        'customer_name' => $post['fullname'],
                        'subject' => $adminSubject,
                        'message'   => $message
                    ];
             $from = ['email' => $fromEmail, 'name' => $post['fullname']];
            // $customerFrom = ['email' => $custfrom, 'name' => "The Shop India"];
             $to=$this->scopeConfig->getValue(
                 'trans_email/ident_sales/email',
                 \Magento\Store\Model\ScopeInterface::SCOPE_STORE
             );
    
            // if(!empty($this->piblock->getAdminEmail())){
            //     $to = $this->piblock->getAdminEmail();
            // }
            if (!empty($this->piblock->getCC())) {
                $ccs=   $this->piblock->getCC();
            } else {
                $ccs='';
            }
            
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
                    ->addTo($to)
                    ->addCc($ccs)
                    ->getTransport();
             $transport->sendMessage();
    
             $this->inlineTranslation->resume();
        }
        if ($this->piblock->isAutoRespEnable()) {
            $From='nitin.sharma@purplecommerce.com';
            $To=$post['email'];
            $txt = '<table>';
            $tmpl=1;
            if ($this->piblock->autoRespMessage()) {
                $txt.='<tr><td><strong>'.$this->piblock->autoRespMessage().'</strong></td></tr>';
            }
            
            $txt.='</table>';
            $message=$txt;
            $userSubject= 'Thank you for enquiring '.$post['psku'];
            // $this->inlineTranslation->suspend();
            $storeScopea = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    
             $templateOptionsa = [
              'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
              'store' => 1
             ];
             $templateVarsa = [
                'store' => 1,
                'customer_name' => $post['fullname'],
                'subject' => $userSubject,
                'message'   => $message
             ];
             $froma = ['email' => $From, 'name' => 'Product Enquiry Response'];
             $postObjecta = new \Magento\Framework\DataObject();
             $postObjecta->setData($templateVarsa);
             $transport1 = $this->_transportBuilder->setTemplateIdentifier('piemail_template')
                    ->setTemplateOptions($templateOptionsa)
                    ->setTemplateVars(['data' => $postObjecta])
                    ->setFrom($froma)
                    ->addTo($To)
                    ->getTransport();
             $transport1->sendMessage();
             $this->inlineTranslation->resume();
            
        }
        //--------->
        if (!empty($this->piblock->getSuccessMsg())) {
            // @codingStandardsIgnoreLine
            $msg = $this->piblock->getSuccessMsg();
            
        } else {
            // @codingStandardsIgnoreLine
            $msg = 'Form successfully submitted';
        }
        $this->_messageManager->addSuccess(__($msg));
    }
}
