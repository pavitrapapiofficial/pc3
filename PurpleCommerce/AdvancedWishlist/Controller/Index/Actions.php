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

class Actions extends Action
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
        $storeUrl = $this->_storeManager->getStore()->getBaseUrl();
        // die;
        
        if (isset($post['type']) && $post['type']=='share') {
            $primary_emials = $post['emails'];
            $customerid=$post['customer_id'];
            $wid = $post['wishlist_id'];
            $customer = $this->piblock->getCustomerDataById($customerid);
            $this->piblock->insertShareList($post, $customer->getFirstName());
            $email=$customer->getEmail();
            $m=$this->piblock->getCampaignMedium();
            $utms = $this->piblock->getCampaignSource();
            $txt = '<table>';
            if ($post['message']) {
                $txt.='<tr><td>'.$post['message'].'</td></tr>';
            }
            // @codingStandardsIgnoreStart
            $txt.='<tr><td>Take a look of my wishlist here: '.$storeUrl.'/advancedwishlist/index/visitor?utm_source='.$utms.'?utm_medium='.$m.'?utm_campaign'.$email.'</td></tr>';
            // @codingStandardsIgnoreEnd       
            $txt.='</table>';
            $recipients=[];
            if ($primary_emials!='') {
                $recipients = explode(',', $primary_emials);
            }

            $message=$txt;
            $adminSubject = "Take a look at ".$customer->getFirstName()."'s wishlist";
            ;
            
            $fromEmail= $customer->getEmail();
            $templateVars = [
                'store' => 1,
                'customer_name' =>$customer->getFirstName(),
                'subject' => $adminSubject,
                'message'   => $message
            ];
            $from = ['email' => $fromEmail, 'name' => $customer->getFirstName()];

            $to=$this->scopeConfig->getValue(
                'trans_email/ident_sales/email',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            
            $this->inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

                $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => 1
                ];
                $postObject = new \Magento\Framework\DataObject();
                $postObject->setData($templateVars);

                if (!empty($recipients)) {
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
                $msg='Wishlist shared successfully';
        } else {
            // @codingStandardsIgnoreStart
            $wid = $_GET['wid'];
            $pids = [];
            $wishlistdata = $this->piblock->getWishlistsItemsById($wid);
            foreach ($wishlistdata as $k => $v) {
                $pids[]=$wishlistdata[$k]['product_id'];
            }
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $cart = $this->_objectManager->get('Magento\Checkout\Model\Cart');
            
            // echo '<pre>';print_r($pids);
            foreach ($pids as $k => $v) {
                // load child product
                $childId = $v;
                $childProduct = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($childId);
                /* Prepare cart params */
                $params = [];
                $params['product'] = $childProduct->getId();
                $params['qty'] = 1;
                $options = [];

                /*Add product to cart */
                $cart->addProduct($childProduct, $params);
                
            }
            // @codingStandardsIgnoreEnd
            $cart->save();
            $msg='Product(s) added successfully';
            $this->_redirect('checkout/cart/');
        }
        
        $this->_messageManager->addSuccess(__($msg));
        // echo $msg;
    }
}
