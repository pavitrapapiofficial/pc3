<?php


namespace PurpleCommerce\ProductEnquiry\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductEnquiryRepositoryInterface
{
    /**
     * Save ProductEnquiry
     *
     * @param  \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface $changelog
     * @return \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface $changelog
    );

    /**
     * Retrieve ProductEnquiry
     *
     * @param  string $EntityId
     * @return \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($changelogId);

    /**
     * Retrieve ProductEnquiry matching the specified criteria.
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquirySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Changelog
     *
     * @param  \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface $changelog
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \PurpleCommerce\ProductEnquiry\Api\Data\ProductEnquiryInterface $changelog
    );

    /**
     * Delete Changelog by ID
     *
     * @param  string $changelogId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($changelogId);

    /**
     * Delete Changelog by ID
     *
     * @param  string $changelogId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveNew($rewardData);
}
