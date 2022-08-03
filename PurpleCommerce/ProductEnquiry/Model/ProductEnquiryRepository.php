<?php


namespace PurpleCommerce\ProductEnquiry\Model;

use Magento\Framework\Api\SortOrder;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\NoSuchEntityException;
// @codingStandardsIgnoreStart
use PurpleCommerce\ProductEnquiry\Model\ResourceModel\ProductEnquiry\CollectionFactory as ProductEnquiryCollectionFactory;
// @codingStandardsIgnoreEnd
use PurpleCommerce\ProductEnquiry\Model\ResourceModel\ProductEnquiry as ResourceProductEnquiry;
use PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use PurpleCommerce\ProductEnquiry\Api\ProductEnquiryRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Store\Model\StoreManagerInterface;

class ProductEnquiryRepository implements ProductEnquiryRepositoryInterface
{

    protected $giftcertificateCollectionFactory;

    protected $resource;

    protected $giftcertificateFactory;

    protected $dataObjectHelper;

    protected $dataProductEnquiryFactory;

    private $storeManager;

    protected $dataObjectProcessor;
    /**
     * @param ResourceProductEnquiry $resource
     * @param ProductEnquiryFactory $giftcertificateFactory
     * @param ProductEnquiryInterfaceFactory $dataProductEnquiryFactory
     * @param ProductEnquiryCollectionFactory $giftcertificateCollectionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceProductEnquiry $resource,
        ProductEnquiryFactory $giftcertificateFactory,
        ProductEnquiryInterfaceFactory $dataProductEnquiryFactory,
        ProductEnquiryCollectionFactory $giftcertificateCollectionFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->giftcertificateFactory = $giftcertificateFactory;
        $this->giftcertificateCollectionFactory = $giftcertificateCollectionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataProductEnquiryFactory = $dataProductEnquiryFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface $giftcertificate
    ) {
        /* if (empty($giftcertificate->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $giftcertificate->setStoreId($storeId);
        } */
        try {
            $this->resource->save($giftcertificate);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the giftcertificate: %1',
                $exception->getMessage()
            ));
        }
        return $giftcertificate;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($giftcertificateId)
    {
        $giftcertificate = $this->giftcertificateFactory->create();
        $this->resource->load($giftcertificate, $giftcertificateId);
        if (!$giftcertificate->getId()) {
            throw new NoSuchEntityException(__('giftcertificate with id "%1" does not exist.', $giftcertificateId));
        }
        return $giftcertificate;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->giftcertificateCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $fields[] = $filter->getField();
                $condition = $filter->getConditionType() ?: 'eq';
                $conditions[] = [$condition => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface $giftcertificate
    ) {
        try {
            $this->resource->delete($giftcertificate);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the giftcertificate: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($giftcertificateId)
    {
        return $this->delete($this->getById($giftcertificateId));
    }

/**
 * {@inheritdoc}
 */
    public function saveNew($rewardData)
    {
        //echo "Inside SaveNew";
        
        $fieldValues = $this->request->getParams();
        if (isset($fieldValues['cancel'])) {
            $this->session->unsProductEnquiryInfo();
            return [];
        }
        $customerRewards = $fieldValues['number_of_rewards'];
        $helper = $this->_rewardSystemHelper;
        //$maxRewardUsed = $helper->getRewardCanUsed();
        if ($fieldValues['used_reward_points'] > $customerRewards) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You don\'t have sufficient Reward Points to use.')
            );
        }
        // if ($fieldValues['used_reward_points'] > $maxRewardUsed) {
        //     throw new \Magento\Framework\Exception\LocalizedException(
        //         __('You can not use more than %1 reward points for this order purchase.', $maxRewardUsed)
        //     );
        // }
        $quote = $this->chkoutSession->getQuote();
        $grandTotal = $quote->getGrandTotal();
        $perRewardAmount = 1;
        $perRewardAmount = $helper->currentCurrencyAmount($perRewardAmount);
        $rewardAmount = $fieldValues['used_reward_points']*$perRewardAmount;
        if ($grandTotal > $rewardAmount) {
            $flag = 0;
            $amount = 0;
            $availAmount = $customerRewards*$perRewardAmount;
            $rewardInfo = $this->session->getProductEnquiryInfo();
            if (!$rewardInfo) {
                $amount = $rewardAmount;
                $rewardInfo = [];
                $rewardInfo = [
                   'used_reward_points' => $fieldValues['used_reward_points'],
                   'number_of_rewards' => $customerRewards,
                   'avail_amount' => $availAmount,
                   'amount' => $amount
                ];
            } else {
                if (is_array($rewardInfo)) {
                    $rewardInfo['used_reward_points'] = $fieldValues['used_reward_points'];
                    $rewardInfo['number_of_rewards'] = $customerRewards;
                    $amount = $rewardAmount;
                    $rewardInfo['amount'] = $amount;

                    $flag = 1;
                }
                if ($flag == 0) {
                    $amount = $rewardAmount;
                    $rewardInfo= [
                       'used_reward_points' => $fieldValues['used_reward_points'],
                       'number_of_rewards' => $customerRewards,
                       'avail_amount' => $availAmount,
                       'amount' => $amount
                    ];
                }
            }
             $this->session->setProductEnquiryInfo($rewardInfo);
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Reward Amount can not be greater than or equal to Order Total...')
            );
        }
        $rewardsInfo[] =$rewardInfo;
        return $rewardsInfo;
    }
}
