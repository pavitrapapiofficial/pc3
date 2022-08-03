<?php
namespace PurpleCommerce\AdvancedWishlist\Block;

use Magento\Framework\Encryption\EncryptorInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PurpleCommerce\AdvancedWishlist\Helper\Data
     */
    protected $_dataHelper;
    protected $_connection;
    protected $_urlInterface;
    protected $_storeManager;
    
    // protected $_customer;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PurpleCommerce\AdvancedWishlist\Helper\Data $dataHelper
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \PurpleCommerce\AdvancedWishlist\Helper\Data $dataHelper,
        EncryptorInterface $encryptor,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Backend\Model\Url $backendUrlManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Customer\Model\SessionFactory $customer,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerDetail,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_customer = $customer;
        $this->httpContext = $httpContext;
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

    public function getCustomerIsLoggedIn()
    {
    	return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getCustomerId()
    {
    	if($this->getCustomerIsLoggedIn()){
            return $customerId = $this->_customer->create()->getCustomer()->getId();
        }
    }

    public function getCustomerName()
    {
    	return $this->httpContext->getValue('customer_name');
    }

    public function getCustomerEmail()
    {
    	return $this->httpContext->getValue('customer_email');
    }

    public function getLocatorData($path)
    {
        return $this->_dataHelper->getconfig($path);
    }

    public function getCampaignSource()
    {
        return $this->_dataHelper->getCampaignSource();
    }

    public function getCampaignMedium()
    {
        return $this->_dataHelper->getCampaignMedium();
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
        
        return $customer;
    }

    public function getWishlistItemData($wid)
    {
        $customer = $this->getCustomerData();
        $customerId = $this->getCustomerId();
        // $customeremail = $customerData->getCustomer()->getEmail();
        // $myTable = $this->_connection->getTableName('table_name');
        // @codingStandardsIgnoreStart
        $result = $this->_connection->fetchAll("SELECT * FROM pc_wishlist_items where wishlist_id='".$wid."'");
        
        // @codingStandardsIgnoreEnd
        return $result;
    }

    public function getWishlist()
    {
        $customer = $this->getCustomerData();
        $customerId = $this->getCustomerId();
        // $customeremail = $customerData->getCustomer()->getEmail();
        // $myTable = $this->_connection->getTableName('table_name');
        // @codingStandardsIgnoreStart
        $result = $this->_connection->fetchAll("SELECT * FROM pc_wishlist where customer_id='".$customerId."'");
        
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
    public function insertWishlistItem($data)
    {
        $wishlistId=$data['wishlistname'];
        $productid = $data['productid'];
                
        $sql = "Insert Into pc_wishlist_items (`product_id`, `wishlist_id`, `sortorder`) Values ($productid,$wishlistId,0)";
        
        if ($this->_connection->query($sql)) {
            $msg='Message Sent.';
        } else {
            $msg = 'Error sending reply.';
        }
        
        return $msg;
    }

    public function createWishlistAndItem($data) {
        $wishlistname=$data['wishlistname'];
        $productid = $data['productid'];
        $customerid = $data['customerId'];
        $cdate=$this->date->gmtDate();
        $sql = "Insert Into pc_wishlist (`customer_id`, `wishlist_name`, `is_default` ,`sortorder`, `created_at`) Values ($customerid,'$wishlistname',1,0,'$cdate')";
        $this->_connection->query($sql);
        $sqltofetchid="select wishlist_id FROM pc_wishlist order by wishlist_id desc limit 1";
        $latestIdResults= $this->_connection->fetchAll($sqltofetchid);
        $wid = 0;
        foreach ($latestIdResults as $id) {
                $wid = $id["wishlist_id"];
        }
        $sql2 = "Insert Into pc_wishlist_items (`product_id`, `wishlist_id`, `sortorder`) Values ($productid,$wid,0)";
        $this->_connection->query($sql2);
    }

    public function createWishlistOnly($data)
    {
        $wishlistname=$data['wishlistname'];
        $customerid = $data['customerId'];
        $cdate=$this->date->gmtDate();
        $sql = "Insert Into pc_wishlist (`customer_id`, `wishlist_name`, `is_default` ,`sortorder`, `created_at`) Values ($customerid,'$wishlistname',1,0,'$cdate')";
        $this->_connection->query($sql);
    }

    public function remaneWishlist($data)
    {
        $wishlistid=$data['wishlistId'];
        $newname = $data['wishlistname'];
        
        $sql = "Update pc_wishlist SET `wishlist_name`='".$newname."' where wishlist_id=".$wishlistid;
        $this->_connection->query($sql);
    }

    public function deleteWishlist($data)
    {
        $wishlistid=$data['wishlistId'];
        
        $sql = "Delete from pc_wishlist where wishlist_id=".$wishlistid;
        $this->_connection->query($sql);
    }

    public function deleteItemWishlist($data)
    {
        $wishlistid=$data['wishlistId'];
        $pid = $data['pidId'];
        
        $sql = "Delete from pc_wishlist_items where product_id=".$pid." and wishlist_id=".$wishlistid;
        $this->_connection->query($sql);
    }

    public function insertShareList($data,$sharedby)
    {
        $ids=$data['emails'];
        $customer_id = $data['customer_id'];
        $wishlistId =  $data['wishlist_id'];
        $cdate=$this->date->gmtDate();
        $sql = "Insert Into pc_shared_wishlists (`receiver_email_ids`,`customer_id`, `wishlist_id`, `name`, `created_at`) Values ('$ids',$customer_id,$wishlistId,'$sharedby','$cdate')";
        
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

    public function getWishlistsByCustomer()
    {
        if($this->getCustomerIsLoggedIn()){
            $customerId = $this->_customer->create()->getCustomer()->getId();
        }
        
        // $id = $customer->getId();
        // @codingStandardsIgnoreStart
        $sqltofetchid="select * FROM pc_wishlist where customer_id=".$customerId;     
        $results= $this->_connection->fetchAll($sqltofetchid);
        // @codingStandardsIgnoreEnd
        
        return $results;
    }

    public function getWishlistsItemsById($id)
    {
        // $id = $customer->getId();
        // @codingStandardsIgnoreStart
        $sqltofetchid="select * FROM pc_wishlist_items where wishlist_id=".$id;     
        $results= $this->_connection->fetchAll($sqltofetchid);
        // @codingStandardsIgnoreEnd
        
        return $results;
    }
    // @codingStandardsIgnoreEnd

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
