<?php
namespace PurpleCommerce\AdvancedWishlist\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const MODULE_ENABLE = "advancedwishlist/general/enable";
    const PRIMARY_EMAIL = "advancedwishlist/general/include_price";
    const ESC_EMAIL = "advancedwishlist/general/campaign_medium";
    const ESC_DAYS = "advancedwishlist/general/campaign_source";
   
    public function getDefaultConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }

    public function isModuleEnabled()
    {
        return (bool) $this->getDefaultConfig(self::MODULE_ENABLE);
    }
    public function getIfSendPrice()
    {
        return $this->getDefaultConfig(self::PRIMARY_EMAIL);
    }
    public function getCampaignSource()
    {
        return $this->getDefaultConfig(self::ESC_EMAIL);
    }
    public function getCampaignMedium()
    {
        return $this->getDefaultConfig(self::ESC_DAYS);
    }

    public function getconfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
