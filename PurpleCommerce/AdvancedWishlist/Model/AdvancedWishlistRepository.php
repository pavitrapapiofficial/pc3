<?php


namespace PurpleCommerce\AdvancedWishlist\Model;

use Magento\Framework\Api\SortOrder;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\NoSuchEntityException;
// @codingStandardsIgnoreStart
use PurpleCommerce\AdvancedWishlist\Model\ResourceModel\AdvancedWishlist\CollectionFactory as AdvancedWishlistCollectionFactory;
// @codingStandardsIgnoreEnd
use PurpleCommerce\AdvancedWishlist\Model\ResourceModel\AdvancedWishlist as ResourceAdvancedWishlist;
use PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use PurpleCommerce\AdvancedWishlist\Api\AdvancedWishlistRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Store\Model\StoreManagerInterface;

class AdvancedWishlistRepository implements AdvancedWishlistRepositoryInterface
{

    protected $giftcertificateCollectionFactory;

    protected $resource;

    protected $giftcertificateFactory;

    protected $dataObjectHelper;

    protected $dataAdvancedWishlistFactory;

    private $storeManager;

    protected $dataObjectProcessor;
    /**
     * @param ResourceAdvancedWishlist $resource
     * @param AdvancedWishlistFactory $giftcertificateFactory
     * @param AdvancedWishlistInterfaceFactory $dataAdvancedWishlistFactory
     * @param AdvancedWishlistCollectionFactory $giftcertificateCollectionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceAdvancedWishlist $resource,
        AdvancedWishlistFactory $giftcertificateFactory,
        AdvancedWishlistInterfaceFactory $dataAdvancedWishlistFactory,
        AdvancedWishlistCollectionFactory $giftcertificateCollectionFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->giftcertificateFactory = $giftcertificateFactory;
        $this->giftcertificateCollectionFactory = $giftcertificateCollectionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAdvancedWishlistFactory = $dataAdvancedWishlistFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface $giftcertificate
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
        \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface $giftcertificate
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
            $this->session->unsAdvancedWishlistInfo();
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
            $rewardInfo = $this->session->getAdvancedWishlistInfo();
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
             $this->session->setAdvancedWishlistInfo($rewardInfo);
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Reward Amount can not be greater than or equal to Order Total...')
            );
        }
        $rewardsInfo[] =$rewardInfo;
        return $rewardsInfo;
    }
}
