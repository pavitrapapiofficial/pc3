<?php
/**
 * PurpleCommerce Support Controller
 *
 * @category    PurpleCommerce
 * @package     PurpleCommerce_Support
 * @author      PurpleCommerce Software Private Limited
 *
 */
namespace PurpleCommerce\Support\Controller\Adminhtml\Tickets;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * Support List page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('PurpleCommerce_Support::view');
        $resultPage->getConfig()->getTitle()->prepend(__('Details'));

        return $resultPage;
    }

    /**
     * Check Support List Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PurpleCommerce_Support::view');
    }
}
