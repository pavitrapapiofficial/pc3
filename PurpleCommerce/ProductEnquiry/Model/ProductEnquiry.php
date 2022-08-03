<?php

/**
 * ProductEnquiry ProductEnquiry Model.
 * @category  PurpleCommerce
 * @package   PurpleCommerce_ProductEnquiry
 * @author    PurpleCommerce
 * @copyright Copyright (c) 2010-2017 PurpleCommerce Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace PurpleCommerce\ProductEnquiry\Model;

use PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface;

class ProductEnquiry extends \Magento\Framework\Model\AbstractModel implements ProductEnquiryInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'pc_productenquiry_records';

    /**
     * @var string
     */
    protected $_cacheTag = 'pc_productenquiry_records';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'pc_productenquiry_records';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreStart
        $this->_init('PurpleCommerce\ProductEnquiry\Model\ResourceModel\ProductEnquiry');
        // @codingStandardsIgnoreEnd
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get Icon.
     *
     * @return varchar
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set Icon.
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
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
     * Get FileName.
     *
     * @return varchar
     */
    public function getSubject()
    {
        return $this->getData(self::SUBJECT);
    }

    /**
     * Set FileName.
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
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * Set FileLabel.
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * Get VisibleTo.
     *
     * @return varchar
     */
    public function getTelephone()
    {
        return $this->getData(self::TELEPHONE);
    }

    /**
     * Set VisibleTo.
     */
    public function setTelephone($telephone)
    {
        return $this->setData(self::TELEPHONE, $telephone);
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
    public function getRemarks()
    {
        return $this->getData(self::REMARKS);
    }

    /**
     * Set UpdateTime.
     */
    public function setRemarks($remarks)
    {
        return $this->setData(self::REMARKS, $remarks);
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
}
