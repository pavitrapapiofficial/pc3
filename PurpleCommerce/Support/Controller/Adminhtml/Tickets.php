<?php


namespace PurpleCommerce\Support\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Tickets extends \Magento\Backend\App\Action
{

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PurpleCommerce_Support::support');
    }
}
