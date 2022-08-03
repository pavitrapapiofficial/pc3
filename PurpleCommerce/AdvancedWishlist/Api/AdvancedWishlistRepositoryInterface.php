<?php


namespace PurpleCommerce\AdvancedWishlist\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface AdvancedWishlistRepositoryInterface
{
    /**
     * Save AdvancedWishlist
     *
     * @param  \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface $changelog
     * @return \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface $changelog
    );

    /**
     * Retrieve AdvancedWishlist
     *
     * @param  string $EntityId
     * @return \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($changelogId);

    /**
     * Retrieve AdvancedWishlist matching the specified criteria.
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Changelog
     *
     * @param  \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface $changelog
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \PurpleCommerce\AdvancedWishlist\Api\Data\AdvancedWishlistInterface $changelog
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
