<?php
namespace PurpleCommerce\AdvancedWishlist\Block;
use Magento\Customer\Model\Context;
use Magento\Customer\Block\Account\SortLinkInterface;


class Link extends \Magento\Framework\View\Element\Html\Link implements SortLinkInterface
{
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Element\Template\Context $context
    ) {

        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $enableConfig = $this->scopeConfig->getValue('advancedwishlist/general/enable', $storeScope);
        if ($enableConfig) { // replace with your condition
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * {@inheritdoc}
     * @since 100.2.0
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}