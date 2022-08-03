<?php


namespace PurpleCommerce\Support\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SupportRepositoryInterface
{
    /**
     * Save Support
     *
     * @param  \PurpleCommerce\Support\Api\Data\SupportInterface $changelog
     * @return \PurpleCommerce\Support\Api\Data\SupportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \PurpleCommerce\Support\Api\Data\SupportInterface $changelog
    );

    /**
     * Retrieve Support
     *
     * @param  string $EntityId
     * @return \PurpleCommerce\Support\Api\Data\SupportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($changelogId);

    /**
     * Retrieve Support matching the specified criteria.
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \PurpleCommerce\Support\Api\Data\SupportSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Changelog
     *
     * @param  \PurpleCommerce\Support\Api\Data\SupportInterface $changelog
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \PurpleCommerce\Support\Api\Data\SupportInterface $changelog
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
