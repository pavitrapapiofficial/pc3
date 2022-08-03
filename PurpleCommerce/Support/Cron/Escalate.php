<?php

namespace PurpleCommerce\Support\Cron;

class Escalate
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
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Backend\Model\Url $backendUrlManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Customer\Model\Session $customer,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerDetail
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_customer = $customer;
        $this->date = $date;
        $this->_storeManager = $storeManager;
        $this->formKey = $formKey;
        $this->_urlInterface = $urlInterface;
        $this->customerDetail = $customerDetail;
        $this->backendUrlManager  = $backendUrlManager;
        $this->_connection = $resource->getConnection();
    }

	public function execute()
	{
        $result = $this->_connection->fetchAll("SELECT * FROM pc_customer_queries where `status`!=2");
        foreach ($result as $k => $value) {
            $this->sendEmails($result[$k]);
        }
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cron.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
		$logger->info(__METHOD__);

		return $this;

	}

    public function sendEmails($post)
    {
        $primary_emials = $this->_dataHelper->getPrimaryEmails();
        $esc_emials = $this->_dataHelper->getEscEmails();
        $esc_days = $this->_dataHelper->getEscDays();
        if (isset($post['ticket_id'])) {
            $tkey = $this->piblock->getTicketkeyById($post['ticket_id']);
        } else {
            $tkey = $post['ticket_key'];
        }
        
        if ($post['status']==0) {
            $status = '<spna style="color:#C31919">Open</span>';
        } elseif ($post['status']==1) {
            $status = '<spna style="color:#ea8600">In Process</span>';
        } else {
            $status = '<spna style="color:#0DA513">Closed</span>';
        }

        if ($post['severity']==0) {
            $severity = '<spna style="color:#C31919">Medium</span>';
        } elseif ($post['severity']==1) {
            $severity = '<spna style="color:#C31919">High</span>';
        } else {
            $severity = '<spna style="color:#C31919">Low</span>';
        }
        
        $txt = '<table>';
        if ($severity) {
            $txt.='<tr><td><strong>Severity</strong>:'.$severity.'</td></tr>';
        }
        if ($status) {
            $txt.='<tr><td><strong>Ticket Status</strong>:'.$status.'</td></tr>';
        }
        if ($tkey) {
            $txt.='<tr><td><strong>Ticket Key</strong>:'.$tkey.'</td></tr>';
        }
        if ($post['customer_name']) {
            $txt.='<tr><td><strong>Customer Name</strong>:'.$post['customer_name'].'</td></tr>';
        }
        if ($post['customer_email']) {
            $txt.='<tr><td><strong>Customer Email</strong>:'.$post['customer_email'].'</td></tr>';
        }
        if ($post['sub']) {
            $txt.='<tr><td><strong>Subject</strong>:'.$post['sub'].'</td></tr>';
        }
        
        if ($post['customer_query']) {
            $txt.='<tr><td><strong>Message</strong>:'.$post['customer_query'].'</td></tr>';
        }
        if ($post['created_at']) {
            $txt.='<tr><td><strong>Created at</strong>:'.$query['created_at'].'</td></tr>';
        }

        $txt.='</table>';
        
        $tmpl=1;
        
        $message=$txt;
        $adminSubject = 'New Message from Ticket key - #'.$tkey;
        
        $fromEmail= $this->scopeConfig->getValue(
            'trans_email/ident_support/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $custfrom = 'info@theshopindia.com';

            $templateVars = [
                    'store' => 1,
                    'customer_name' => $post['customer_name'],
                    'subject' => $adminSubject,
                    'message'   => $message
                ];
            $from = ['email' => $fromEmail, 'name' => $post['customer_name']];
        // $customerFrom = ['email' => $custfrom, 'name' => "The Shop India"];
            // $to=$this->scopeConfig->getValue(
            //     'trans_email/ident_sales/email',
            //     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            // );

        // if(!empty($this->piblock->getAdminEmail())){
        //     $to = $this->piblock->getAdminEmail();
        // }
            $recipients = explode(',', $esc_emials);
            $emails=[];
        
        //  $primary_emials;
        // print_r()
            $to=$emails;
            $this->inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => 1
            ];
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($templateVars);
            foreach ($recipients as $v) {
                $transport = $this->_transportBuilder->setTemplateIdentifier('piemail_template')
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($from)
                ->addTo($v)
                ->getTransport();
                $transport->sendMessage();
            }
            $this->inlineTranslation->resume();
    }
}
