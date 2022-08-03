<?php
/**
 * PurpleCommerce_Support Status Options Model.
 * @category    PurpleCommerce
 * @author      PurpleCommerce Software Private Limited
 */
namespace PurpleCommerce\Support\Model;

use Magento\Framework\Data\OptionSourceInterface;

class Severity implements OptionSourceInterface
{
    /**
     * Get Support row status type labels array.
     * @return array
     */
    public function getOptionArray()
    {
        $options = [
            '1' => __('High'),
            '0' => __('Medium'),
            '2' => __('Low')
        ];
        return $options;
    }

    /**
     * Get Support row status labels array with empty value for option element.
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
     * Get Support row type array for option element.
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
