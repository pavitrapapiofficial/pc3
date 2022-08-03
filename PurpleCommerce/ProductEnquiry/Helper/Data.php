<?php
namespace PurpleCommerce\ProductEnquiry\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const MODULE_ENABLE = "productenquiry/general/enable";
    const PE_ONE = "productenquiry/general/pe_for_all";
    const PE_TWO = "productenquiry/general/pe_on_listing";
    const PE_THREE = "productenquiry/general/pe_on_detail";
    const PE_FOUR = "productenquiry/general/pe_for_cat";
    const PE_FIVE = "productenquiry/general/pe_button_text";
    const PE_SIX = "productenquiry/formconfig/pe_from_title";

    const PE_SEVEN = "productenquiry/formconfig/show_prod_data";
    const PE_EIGHT = "productenquiry/formconfig/pe_success_msg";
    const PE_NINE = "productenquiry/formconfig/pe_custom_one";
    const PE_TEN = "productenquiry/formconfig/pe_custom_two";
    const PE_ELEVEN = "productenquiry/formconfig/pe_custom_three";
    const PE_TWELVE = "productenquiry/formconfig/pe_custom_four";

    const PE_THIRTEEN = "productenquiry/formconfig/pe_email_admin";
    const PE_FOURTEEN = "productenquiry/formconfig/pe_email_of_admin";
    const PE_FIFTEEN = "productenquiry/formconfig/pe_email_cc";
    const PE_SIXTEEN = "productenquiry/formconfig/pe_auto_response";
    const PE_SEVENTEEN = "productenquiry/formconfig/pe_autoresp_email";
    const PE_EIGHTEEN = "productenquiry/formconfig/pe_autoresp_msg";
   
    const PE_NINETEEN = "productenquiry/formconfig/pe_from_des";
   
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

    public function isForAll()
    {
        return (bool) $this->getDefaultConfig(self::PE_ONE);
    }
    public function isOnListing()
    {
        return (bool) $this->getDefaultConfig(self::PE_TWO);
    }
    public function isOnDetail()
    {
        return (bool) $this->getDefaultConfig(self::PE_THREE);
    }
    public function visibleCategories()
    {
        return $this->getDefaultConfig(self::PE_FOUR);
    }
    public function getButtonText()
    {
        return $this->getDefaultConfig(self::PE_FIVE);
    }
    public function getFormTitle()
    {
        return $this->getDefaultConfig(self::PE_SIX);
    }
    public function showProdData()
    {
        return (bool) $this->getDefaultConfig(self::PE_SEVEN);
    }
    public function getSuccessMsg()
    {
        return $this->getDefaultConfig(self::PE_EIGHT);
    }
    public function getCustomOne()
    {
        return $this->getDefaultConfig(self::PE_NINE);
    }
    public function getCustomTwo()
    {
        return $this->getDefaultConfig(self::PE_TEN);
    }
    public function getCustomThree()
    {
        return $this->getDefaultConfig(self::PE_ELEVEN);
    }
    public function getCustomFour()
    {
        return $this->getDefaultConfig(self::PE_TWELVE);
    }
    public function sendEmailToAdmin()
    {
        return (bool) $this->getDefaultConfig(self::PE_THIRTEEN);
    }
    public function getAdminEmail()
    {
        return $this->getDefaultConfig(self::PE_FOURTEEN);
    }

    public function getCC()
    {
        return $this->getDefaultConfig(self::PE_FIFTEEN);
    }
    public function isAutoRespEnable()
    {
        return (bool) $this->getDefaultConfig(self::PE_SIXTEEN);
    }
    public function getAutoRespEmail()
    {
        return $this->getDefaultConfig(self::PE_SEVENTEEN);
    }
    public function autoRespMessage()
    {
        return $this->getDefaultConfig(self::PE_EIGHTEEN);
    }
    public function getFromDesc()
    {
        return $this->getDefaultConfig(self::PE_NINETEEN);
    }
    public function getconfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
