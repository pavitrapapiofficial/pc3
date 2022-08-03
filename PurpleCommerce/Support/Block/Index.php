<?php
namespace PurpleCommerce\Support\Block;

use Magento\Framework\Encryption\EncryptorInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PurpleCommerce\Support\Helper\Data
     */
    protected $_dataHelper;
    protected $_connection;
    protected $_urlInterface;
    protected $_storeManager;
    
    // protected $_customer;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PurpleCommerce\Support\Helper\Data $dataHelper
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \PurpleCommerce\Support\Helper\Data $dataHelper,
        EncryptorInterface $encryptor,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Backend\Model\Url $backendUrlManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Customer\Model\Session $customer,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerDetail,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_customer = $customer;
        $this->date = $date;
        $this->_storeManager = $storeManager;
        $this->formKey = $formKey;
        $this->_urlInterface = $urlInterface;
        $this->customerDetail = $customerDetail;
        $this->backendUrlManager  = $backendUrlManager;
        $this->encryptor = $encryptor;
        $this->_connection = $resource->getConnection();
        parent::__construct($context, $data);
    }

    public function canShowBlock()
    {
        return $this->_dataHelper->isModuleEnabled();
    }

    public function sayHello()
    {
        return 'hello';
    }

    public function getLocatorData($path)
    {
        return $this->_dataHelper->getconfig($path);
    }
    public function getstaticdata()
    {
        $arr=$this->getBaseUrl();
        return $arr.'/pub/test.txt';
    }

    public function getbaseurl()
    {
        return $this->_urlInterface->getBaseUrl();
    }

    public function getCustomerDataById($id)
    {
        $customer = $this->customerDetail->getById($id);
        return $customer;
    }

    public function getCustomerData()
    {
        $customer = $this->_customer;
        $customerName = $customer->getName();
        $customerId = $customer->getId();
        return $customer;
    }

    public function getTableData()
    {
        $customerData = $this->getCustomerData();
        $customeremail = $customerData->getCustomer()->getEmail();
        // $myTable = $this->_connection->getTableName('table_name');
        // @codingStandardsIgnoreStart
        $result = $this->_connection->fetchAll("SELECT * FROM pc_customer_queries where email='".$customeremail."'");
        // @codingStandardsIgnoreEnd
        return $result;
    }

    // @codingStandardsIgnoreStart
    public function InsertCustomerResponse($data)
    {
        
        $sqltofetchid="select ticket_id FROM pc_customer_queries order by ticket_id desc limit 1";
        $latestIdResults= $this->_connection->fetchAll($sqltofetchid);
        $tid = 0;
        foreach ($latestIdResults as $id) {
                $tid = $id["ticket_id"];
        }
        $responderId = $data['customer_id'];
        $message = $data['customer_query'];
        $created_id=$data['created_at'];
        $status = $data['status'];
        $severity = $data['severity'];
        $date = $this->date->gmtDate();
        if (isset($data['attachment'])) {
            $attachment= $data['attachment'];
        } else {
            $attachment = null;
        }
        $sql = "Insert Into pc_helpdesk_tickets (`ticket_id`, `responder_id`, `attachment`, `severity`, `status`, `body`, `created_at`,`updated_at`) Values ($tid,$responderId,'$attachment',$status,$severity,'$message','$date','$date')";
        if ($this->_connection->query($sql)) {
            $msg='Reply Sent.';
        } else {
            $msg = 'Error sending reply.';
        }
        // @codingStandardsIgnoreEnd
        return $msg;
    }

    // @codingStandardsIgnoreStart
    public function InsertCustomerChat($data)
    {
        $tid=$data['ticket_id'];
        $responderId = $data['customer_id'];
        $message = $data['customer_query'];
        $created_id=$data['created_at'];
        $status = $data['status'];
        $severity = $data['severity'];
        $date = $this->date->gmtDate();
        if (isset($data['attachment'])) {
            $attachment= $data['attachment'];
        } else {
            $attachment = null;
        }
        
        $sql = "Insert Into pc_helpdesk_tickets (`ticket_id`, `responder_id`, `attachment`, `severity`, `status`, `body`, `created_at`,`updated_at`) Values ($tid,$responderId,'$attachment',$status,$severity,'$message','$date','$date')";
        
        if ($this->_connection->query($sql)) {
            $msg='Message Sent.';
        } else {
            $msg = 'Error sending reply.';
        }
        // @codingStandardsIgnoreEnd
        return $msg;
    }

    public function updateTicket($val, $tid)
    {
        // @codingStandardsIgnoreStart
        $sql = "Update `pc_helpdesk_tickets` SET `status` =".$val." where `ticket_id`=".$tid."";
        $sql2 = "Update `pc_customer_queries` SET `status` =".$val." where `ticket_id`=".$tid."";
        
        // $this->_connection->query($sql2);
        if ($this->_connection->query($sql) && $this->_connection->query($sql2)) {
            $msg='Ticket has been updated successfully.';
        } else {
            $msg = 'There has been some error updating ticket status.';
        }
        // @codingStandardsIgnoreEnd
        return $msg;
    }

    public function getTicketkeyById($id)
    {
        // @codingStandardsIgnoreStart
        $sqltofetchid="select ticket_key FROM pc_customer_queries where ticket_id=".$id;
        
        $latestIdResults= $this->_connection->fetchAll($sqltofetchid);
        // @codingStandardsIgnoreEnd
        $tkey='';
        foreach ($latestIdResults as $key) {
            $tkey = $key["ticket_key"];
        }
        return $tkey;
    }

    /**
     * Encrypt data
     *
     * @param   string $data
     * @return  string
     */
    public function encryptData($data)
    {
        return $this->encryptor->encrypt($data);
    }

    /**
     * Decrypt data
     *
     * @param   string $data
     * @return  string
     */
    public function decryptData($data)
    {
        return $this->encryptor->decrypt($data);
    }
}
