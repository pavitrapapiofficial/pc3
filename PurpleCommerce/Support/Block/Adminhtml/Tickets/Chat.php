<?php
namespace PurpleCommerce\Support\Block\Adminhtml\Tickets;

use Magento\Framework\Encryption\EncryptorInterface;

class Chat extends \Magento\Framework\View\Element\Template
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
        \Magento\Customer\Api\CustomerRepositoryInterface $customer,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_customer = $customer;
        $this->date = $date;
        $this->_storeManager = $storeManager;
        $this->formKey = $formKey;
        $this->_urlInterface = $urlInterface;
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

    /**
     * get form key
     *
     * @return string
     */
    public function getFormKey()
    {
         return $this->formKey->getFormKey();
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
        $customer = $this->_customer->getById($id);
        return $customer;
    }

    public function getQueryTableData($id)
    {
        // @codingStandardsIgnoreStart
        $result = $this->_connection->fetchAll("SELECT * FROM pc_customer_queries where ticket_id='".$id."'");
        // @codingStandardsIgnoreEnd
        return $result;
    }
    
    // @codingStandardsIgnoreStart
    public function InsertAgentResponse($data)
    {
        
        $sqltofetchid="select ticket_key,status,severity FROM pc_customer_queries where ticket_id=".$data['ticketid'];
        $latestIdResults= $this->_connection->fetchAll($sqltofetchid);
        $tid = 0;
        $tkey = '';
        foreach ($latestIdResults as $id) {
                $tkey = $id["ticket_key"];
                $status = $id["status"];
                $severity = $id["severity"];
        }
        $tid = $data['ticketid'];
        $responderId = $data['agentid'];
        $message = $data['message'];
        $date = $this->date->gmtDate();
        if (isset($data['attachment'])) {
            $attachment= $data['attachment'];
        } else {
            $attachment = null;
        }
        $sql = "Insert Into pc_helpdesk_tickets (`ticket_id`, `responder_id`, `attachment`, `severity`, `status`, `body`, `created_at`,`updated_at`) Values ($tid,$responderId,'$attachment',$status,$severity,'$message','$date','$date')";
        if ($this->_connection->query($sql)) {
            $msg='Reply Sent for Ticket Key - '.$tkey;
        } else {
            $msg = 'Error sending reply.';
        }
        // @codingStandardsIgnoreEnd
        return $msg;
    }

    public function closeTicket($val, $tid)
    {
        // @codingStandardsIgnoreStart
        $sql = "Update `pc_helpdesk_tickets` SET `status` =".$val." where `ticket_id`=".$tid."";
        $sql2 = "Update `pc_customer_queries` SET `status` =".$val." where `ticket_id`=".$tid."";
        // $this->_connection->query($sql2);
        if ($this->_connection->query($sql) && $this->_connection->query($sql2)) {
            $msg='Ticket has been closed successfully.';
        } else {
            $msg = 'There has been some error updating ticket status.';
        }
        // @codingStandardsIgnoreEnd
        return $msg;
    }

    public function getTableDataById($id)
    {
        // @codingStandardsIgnoreStart
        $result = $this->_connection->fetchAll("SELECT * FROM pc_helpdesk_tickets where ticket_id='".$id."'");
        // @codingStandardsIgnoreEnd
        return $result;
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

    public function getBackendUrl()
    {
        return $this->backendUrlManager->getUrl('tickets/tickets/save');
    }

    public function getUrlInterfaceData()
    {
        return $this->_urlInterface->getCurrentUrl();
    }

    public function getReplyBox($id, $class = '')
    {
        // @codingStandardsIgnoreStart
        $html='<form action="'.$this->getBackendUrl().'" method="post" enctype="multipart/form-data" class="'.$class.' chat-box"><input type="hidden" name="ticketid" value="'.$id.'"><input type="hidden" name="agentid" value="0">'.$this->getBlockHtml("formkey").'
            <input type="file" name="attachment" value=""> 
                <textarea name="message"></textarea><input type="submit" name="submit" value="Send"></form>';
                // @codingStandardsIgnoreEnd
        return $html;
    }
    public function getCloseTicketBox($id, $class = '')
    {
        // @codingStandardsIgnoreStart
        $html='<form action="'.$this->getBackendUrl().'" method="post" enctype="multipart/form-data" class="'.$class.' close-ticket"><input type="hidden" name="ticketid" value="'.$id.'"><input type="hidden" name="agentid" value="0">'.$this->getBlockHtml("formkey").'
        <input type="hidden" name="t-close" value="2"><input type="submit" name="close" value="Close Ticket"></form>';
        // @codingStandardsIgnoreEnd
        return $html;
    }
}
