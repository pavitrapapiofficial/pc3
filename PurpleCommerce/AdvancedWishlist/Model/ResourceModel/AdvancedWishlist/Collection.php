<?php

/**
 * AdvancedWishlist AdvancedWishlist Collection.
 * @category    PurpleCommerce
 * @author      PurpleCommerce Software Private Limited
 */
namespace PurpleCommerce\AdvancedWishlist\Model\ResourceModel\AdvancedWishlist;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'wishlist_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreStart
        $this->_init(
            'PurpleCommerce\AdvancedWishlist\Model\AdvancedWishlist', 
            'PurpleCommerce\AdvancedWishlist\Model\ResourceModel\AdvancedWishlist'
        );
        // @codingStandardsIgnoreEnd
    }
}
