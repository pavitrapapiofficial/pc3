<?php

/**
 * AdvancedWishlist AdvancedWishlist Model.
 * @category  PurpleCommerce
 * @package   PurpleCommerce_AdvancedWishlist
 * @author    PurpleCommerce
 * @copyright Copyright (c) 2010-2017 PurpleCommerce Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace PurpleCommerce\AdvancedWishlist\Model;

use PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface;

class AdvancedWishlist extends \Magento\Framework\Model\AbstractModel implements AdvancedWishlistInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'pc_wishlist';

    /**
     * @var string
     */
    protected $_cacheTag = 'pc_wishlist';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'pc_wishlist';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreStart
        $this->_init('PurpleCommerce\AdvancedWishlist\Model\ResourceModel\AdvancedWishlist');
        // @codingStandardsIgnoreEnd
    }
    /**
     * Get WishlistId.
     *
     * @return int
     */
    public function getWishlistId()
    {
        return $this->getData(self::WISHLIST_ID);
    }

    /**
     * Set WishlistId.
     */
    public function setWishlistId($entityId)
    {
        return $this->setData(self::WISHLIST_ID, $entityId);
    }

    /**
     * Get Icon.
     *
     * @return varchar
     */
    public function getWishlistName()
    {
        return $this->getData(self::WISHLIST_NAME);
    }

    /**
     * Set Icon.
     */
    public function setWishlistName($name)
    {
        return $this->setData(self::WISHLIST_NAME, $name);
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
    public function getIsDefault()
    {
        return $this->getData(self::IS_DEFAULT);
    }

    /**
     * Set VisibleTo.
     */
    public function setIsDefault($telephone)
    {
        return $this->setData(self::IS_DEFAULT, $telephone);
    }

    /**
     * Get IsActive.
     *
     * @return varchar
     */
    public function getSortorder()
    {
        return $this->getData(self::SORTORDER);
    }

    /**
     * Set IsActive.
     */
    public function setSortorder($status)
    {
        return $this->setData(self::SORTORDER, $status);
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
