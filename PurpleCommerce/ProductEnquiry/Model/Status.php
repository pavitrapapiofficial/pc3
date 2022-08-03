<?php
/**
 * PurpleCommerce_ProductEnquiry Status Options Model.
 * @category    PurpleCommerce
 * @author      PurpleCommerce Software Private Limited
 */
namespace PurpleCommerce\ProductEnquiry\Model;
use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**
     * Get ProductEnquiry row status type labels array.
     * @return array
     */
    public function getOptionArray()
    {
        $options = [
            '1' => __('In Process'),
            '0' => __('Pending'),
            '2' => __('Complete')
        ];
        return $options;
    }

    /**
     * Get ProductEnquiry row status labels array with empty value for option element.
     *
     * @return array
     */
    public function getAllOptions()
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);
        return $res;
    }

    /**
     * Get ProductEnquiry row type array for option element.
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}