<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PurpleCommerce\Support\Controller\Adminhtml\Tickets;

use \Magento\Framework\Message\ManagerInterface;
use \Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Backend\App\Action
{
    private $dataPersistor;
    /**
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */

    protected $context;
    protected $_connection;
    protected $filesystem;
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
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */

    public function __construct(
        Context $context,
        Filesystem $filesystem,
        UploaderFactory $fileUploader,
        \PurpleCommerce\Support\Block\Adminhtml\Tickets\Chat $Chatblock,
        \PurpleCommerce\Support\Model\Support $modlefactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->fileUploader = $fileUploader;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->chatblock = $Chatblock;
        $this->_messageManager = $messageManager;
        $this->model = $modlefactory;
    }

    /**
     * Notify user
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */

    public function execute()
    {
        $post = $this->getRequest()->getPost();
        $img=$this->getRequest()->getFiles('attachment');
        if (isset($post['t-close'])) {
            $msg= $this->chatblock->closeTicket($post['t-close'], $post['ticketid']);
        } else {
            if ($post) {
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
                        $post['attachment']= 'media/pc_helpdesk_attachemt/' . $uploader->getUploadedFileName();
                    }
                } catch (\Exception $e) {
                    $this->_messageManager->addError($e->getMessage());
                }
                
                // echo '<pre>';
                // print_r($post);
                // die;
                $msg= $this->chatblock->InsertAgentResponse($post);
                // $msg='Message Sent.';
                // $saved=$this->model->Save();
                
            }
        }
        
        $this->_messageManager->addSuccess(__($msg));
        $this->_redirect('*/*/index');
    }

    public function updateImageName($path, $file_name)
    {
        if ($position = strrpos($file_name, '.')) {
            $name = substr($file_name, 0, $position);
            $extension = substr($file_name, $position);
        } else {
            $name = $file_name;
        }
        $new_file_path = $path . '/' . $file_name;
        $new_file_name = $file_name;
        $count = 0;
        // @codingStandardsIgnoreStart
        while (file_exists($new_file_path)) {
            $new_file_name = $name . '_' . $count . $extension;
            $new_file_path = $path . '/' . $new_file_name;
            $count++;
        }
        // @codingStandardsIgnoreEnd
        return $new_file_name;
    }

    public function uploadFile($filename, $folderName)
    {
        // this folder will be created inside "pub/media" folder
        // $folderName = 'your-custom-folder/';
        
        // "upload_custom_file" is the HTML input file name
        // $filename = 'upload_custom_file';
        // echo '-->'.$filename;
        
        try {
            // echo '-->try';
            $file = $this->getRequest()->getFiles($filename);
            $fileName = ($file && array_key_exists('name', $file)) ? $file['name'] : null;
            
            if ($file && $fileName) {
                
                $target = $this->mediaDirectory->getAbsolutePath($folderName);
                
                /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                $uploader = $this->fileUploader->create(['fileId' => $filename]);
                
                // set allowed file extensions
                $uploader->setAllowedExtensions(['jpg', 'pdf', 'doc', 'png', 'zip']);
                
                // allow folder creation
                $uploader->setAllowCreateFolders(true);
                
                // rename file name if already exists
                $uploader->setAllowRenameFiles(true);
                
                // upload file in the specified folder
                $result = $uploader->save($target);
                
                //echo '<pre>'; print_r($result); exit;
                
                if ($result['file']) {
                    $this->messageManager->addSuccess(__('File has been successfully uploaded.'));
                }
                // die;
                return $target . $uploader->getUploadedFileName();
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        
        return false;
    }
}
