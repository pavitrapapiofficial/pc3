<?php


namespace PurpleCommerce\Support\Controller\Adminhtml\Tickets;

use PurpleCommerce\Support\Controller\Adminhtml\Tickets as SupportController;
use Magento\Framework\Controller\ResultFactory;

class AddRow extends SupportController
{
    
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;
    
    /**
     * @var \PurpleCommerce\Support\Model\SupportFactory
     */
    private $gridFactory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \PurpleCommerce\Support\SupportFactory $gridFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \PurpleCommerce\Support\Model\SupportFactory $gridFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->gridFactory = $gridFactory;
    }
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->gridFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getTitle();
            if (!$rowData->getId()) {
                $this->messageManager->addError(__('row data no longer exist.'));
                $this->_redirect('tickets/tickets/index');
                return;
            }
        }

        $this->coreRegistry->register('row_data', $rowData);
        //    $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        
        $title = $rowId ? __('Edit Ticket').$rowTitle : __('New Ticket');
        $resultPage->setActiveMenu('PurpleCommerce_Support::support');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb($title, $title);
        return $resultPage;
    }
}
