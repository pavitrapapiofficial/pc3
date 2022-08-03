<?php
namespace PurpleCommerce\Support\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const MODULE_ENABLE = "tickets/general/enable";
    const PRIMARY_EMAIL = "tickets/general/pc_primary_email";
    const ESC_EMAIL = "tickets/general/pc_escaltion_email";
    const ESC_DAYS = "tickets/general/pc_escaltion_days";
   
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
    public function getPrimaryEmails()
    {
        return $this->getDefaultConfig(self::PRIMARY_EMAIL);
    }
    public function getEscEmails()
    {
        return $this->getDefaultConfig(self::ESC_EMAIL);
    }
    public function getEscDays()
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
