<?php

/**
 * Support Support Model.
 * @category  PurpleCommerce
 * @package   PurpleCommerce_Support
 * @author    PurpleCommerce
 * @copyright Copyright (c) 2010-2017 PurpleCommerce Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace PurpleCommerce\Support\Model;

use PurpleCommerce\Support\Api\Data\SupportInterface;

class Support extends \Magento\Framework\Model\AbstractModel implements SupportInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'pc_customer_queries';

    /**
     * @var string
     */
    protected $_cacheTag = 'pc_customer_queries';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'pc_customer_queries';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreStart
        $this->_init('PurpleCommerce\Support\Model\ResourceModel\Support');
        // @codingStandardsIgnoreEnd
    }
    /**
     * Get TicketId.
     *
     * @return int
     */
    public function getTicketId()
    {
        return $this->getData(self::TICKET_ID);
    }

    /**
     * Set TicketId.
     */
    public function setTicketId($entityId)
    {
        return $this->setData(self::TICKET_ID, $entityId);
    }

    /**
     * Get Icon.
     *
     * @return varchar
     */
    public function getCustomerName()
    {
        return $this->getData(self::CUSTOMER_NAME);
    }

    /**
     * Set Icon.
     */
    public function setCustomerName($name)
    {
        return $this->setData(self::CUSTOMER_NAME, $name);
    }

    /**
     * Get getAttachmentType.
     *
     * @return varchar
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Set AttachmentType.
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get FileCustomerName.
     *
     * @return varchar
     */
    public function getSubject()
    {
        return $this->getData(self::SUBJECT);
    }

    /**
     * Set FileCustomerName.
     */
    public function setSubject($subject)
    {
        return $this->setData(self::SUBJECT, $subject);
    }

    /**
     * Get FileLabel.
     *
     * @return varchar
     */
    public function getCustomerQuery()
    {
        return $this->getData(self::CUSTOMER_QUERY);
    }

    /**
     * Set FileLabel.
     */
    public function setCustomerQuery($message)
    {
        return $this->setData(self::CUSTOMER_QUERY, $message);
    }

    /**
     * Get FileLabel.
     *
     * @return varchar
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set FileLabel.
     */
    public function setCustomerId($cusid)
    {
        return $this->setData(self::CUSTOMER_ID, $cusid);
    }

    /**
     * Get VisibleTo.
     *
     * @return varchar
     */
    public function getTicketKey()
    {
        return $this->getData(self::TICKET_KEY);
    }

    /**
     * Set VisibleTo.
     */
    public function setTicketKey($telephone)
    {
        return $this->setData(self::TICKET_KEY, $telephone);
    }

    /**
     * Get IsActive.
     *
     * @return varchar
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set IsActive.
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getSeverity()
    {
        return $this->getData(self::SEVERITY);
    }

    /**
     * Set UpdateTime.
     */
    public function setSeverity($remarks)
    {
        return $this->setData(self::SEVERITY, $remarks);
    }

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getAttachment()
    {
        return $this->getData(self::ATTACHMENT);
    }

    /**
     * Set UpdateTime.
     */
    public function setAttachment($attch)
    {
        return $this->setData(self::ATTACHMENT, $attch);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set CreatedAt.
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getUpdateddAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set CreatedAt.
     */
    public function setUpdateddAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
