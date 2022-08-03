<?php
namespace PurpleCommerce\ProductEnquiry\Model\Config\Source;

use Magento\Catalog\Api\CategoryListInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Option\ArrayInterface;

class ConfigOption implements ArrayInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
 
    /**
     * @var CategoryListInterface
     */
    private $categoryList;
    public function __construct(
        CategoryListInterface $categoryList,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->categoryList = $categoryList;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options=[];
        $categoryList = $this->getAllSystemCategory();
        if ($categoryList->getTotalCount()) {
            foreach ($categoryList->getItems() as $category) {
                // var_dump($category->getData());
                $options[] =
                    [
                        'label' => $category->getName(),
                        'value' => $category->getId()
                    ]
                ;
            }
        }
        return $options;
    }

    public function getAllSystemCategory()
    {
        $categoryList = [];
        try {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $categoryList = $this->categoryList->getList($searchCriteria);
        } catch (Exception $exception) {
            // @codingStandardsIgnoreStart
            throw new Exception('Something went wrong. Try after sometime.');
            // @codingStandardsIgnoreEnd
        }
 
        return $categoryList;
    }
}
