<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PurpleCommerce\AdvancedWishlist\Controller\Index;

use \Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Request\DataPersistorInterface;

class Curd extends Action
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
        \PurpleCommerce\AdvancedWishlist\Block\Index $PIblock,
        \PurpleCommerce\AdvancedWishlist\Model\AdvancedWishlist $modlefactory,
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
        
        // die;
        if ($post['type']=='rename') {
            $this->piblock->remaneWishlist($post);
        } elseif ($post['type']=='createnew') {
            $this->piblock->createWishlistOnly($post);
        } elseif ($post['type']=='removeitem') {
            $this->piblock->deleteItemWishlist($post);
        } else {
            $this->piblock->deleteWishlist($post);
        }
        
        //--------->db insertion
        // if ($query) {
            
        //     $this->model->setData($query);
        //     $saved=$this->model->Save();
            
        // }
        //--------->
        $msg='Wishlist added successfully';
        // $this->_messageManager->addSuccess(__($msg));
        // echo $msg;
    }
}
