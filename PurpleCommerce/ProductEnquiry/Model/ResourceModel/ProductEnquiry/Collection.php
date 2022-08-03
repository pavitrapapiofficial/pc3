<?php

/**
 * ProductEnquiry ProductEnquiry Collection.
 * @category    PurpleCommerce
 * @author      PurpleCommerce Software Private Limited
 */
namespace PurpleCommerce\ProductEnquiry\Model\ResourceModel\ProductEnquiry;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreStart
        $this->_init(
            'PurpleCommerce\ProductEnquiry\Model\ProductEnquiry', 
            'PurpleCommerce\ProductEnquiry\Model\ResourceModel\ProductEnquiry'
        );
        // @codingStandardsIgnoreEnd
    }
}
