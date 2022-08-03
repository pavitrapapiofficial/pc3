<?php

namespace PurpleCommerce\AdvancedWishlist\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Action\Context $context
    ) {

        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $enableConfig = $this->scopeConfig->getValue('advancedwishlist/general/enable', $storeScope);
        if ($enableConfig) {
            $this->_view->loadLayout();
            $this->_view->renderLayout();
        }
    }
}
