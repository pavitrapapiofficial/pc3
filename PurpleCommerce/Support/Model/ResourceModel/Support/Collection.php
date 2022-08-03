<?php

/**
 * Support Support Collection.
 * @category    PurpleCommerce
 * @author      PurpleCommerce Software Private Limited
 */
namespace PurpleCommerce\Support\Model\ResourceModel\Support;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'ticket_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreStart
        $this->_init(
            'PurpleCommerce\Support\Model\Support', 
            'PurpleCommerce\Support\Model\ResourceModel\Support'
        );
        // @codingStandardsIgnoreEnd
    }
}
