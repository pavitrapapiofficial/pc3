<?php

namespace PurpleCommerce\Support\Controller\Index;

use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Controller\ResultFactory;

class View extends \Magento\Framework\App\Action\Action
{

    protected $_pageFactory;
    protected $request;
    protected $_customerSession;
    protected $customerGroup;
    protected $attachmentmodelFactory;
    protected $_storeManager;
    /**
     * @var Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_downloader;

    /**
     * @var Magento\Framework\Filesystem\DirectoryList
     */
    protected $_directory;
    protected $_connection;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        EncryptorInterface $encryptor,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem\DirectoryList $directory,
        \Magento\Customer\Model\Session $customer,
        ResultFactory $resultFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \PurpleCommerce\Support\Model\ResourceModel\Support\CollectionFactory $attachmentmodelFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->request = $request;
        $this->_storeManager = $storeManager;
        $this->_downloader =  $fileFactory;
        $this->scopeConfig = $scopeConfig;
        $this->directory = $directory;
        $this->encryptor = $encryptor;
        $this->_customer = $customer;
        $this->resultFactory      = $resultFactory;
        $this->attachmentmodelFactory = $attachmentmodelFactory;
        $this->_customerSession = $session;
        $this->_connection = $resource->getConnection();
        return parent::__construct($context);
    }

    public function execute()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $enableConfig = $this->scopeConfig->getValue('tickets/general/enable', $storeScope);
        if ($enableConfig) {
            $ticket_key = str_replace(' ', '+', $this->request->getParam('key'));
            $decryptkey=$this->encryptor->decrypt($ticket_key);
            $customerData = $this->getCustomerData();
            $customeremail = $customerData->getCustomer()->getEmail();
            // $myTable = $this->_connection->getTableName('table_name');
            $result = $this->_connection->fetchAll("SELECT * FROM pc_customer_queries where ticket_key='".$decryptkey."'");
            $ticketId = $result[0]['ticket_id'];
            $history = $this->_connection->fetchAll("SELECT * FROM pc_helpdesk_tickets where ticket_id=".$ticketId);
            /** @var Page $page */
            $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            // echo '<pre>';
            // print_r($result);
            // die;
            /** @var Template $block */
            $block = $page->getLayout()->getBlock('view_tab');
            $block->setData('result_by_ticket_id', $result);
            $block->setData('history_by_ticket_id', $history);

            $this->_view->loadLayout();
            $this->_view->renderLayout();
        }
    }

    public function getCustomerData()
    {
        $customer = $this->_customer;
        $customerName = $customer->getName();
        $customerId = $customer->getId();
        return $customer;
    }
}
