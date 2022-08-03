<?php
/**
 * PurpleCommerce_AdvancedWishlist AdvancedWishlist Interface.
 *
 * @category    PurpleCommerce
 *
 * @author      PurpleCommerce Software Private Limited
 */
namespace PurpleCommerce\AdvancedWishlist\Api\Data;

interface AdvancedWishlistInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const WISHLIST_ID = 'ticket_id';
    const WISHLIST_NAME = 'customer_name';
    const CUSTOMER_ID = 'customer_id';
    const IS_DEFAULT = 'status';
    const SORTORDER = 'ticket_key';
    const CREATED_AT = 'created_at';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getWishlistId();

    /**
     * Set EntityId.
     */
    public function setWishlistId($entityId);

    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getWishlistName();

    /**
     * Set Title.
     */
    public function setWishlistName($name);

    /**
     * Set PublishDate.
     *
     * @return varchar
     */
    public function getCustomerId();
    /**
     * Set PublishDate.
     */
    public function setCustomerId($message);
    /**
     * Set PublishDate.
     */
    public function getIsDefault();
    
    /**
     * Set PublishDate.
     */
    public function setIsDefault($telephone);

    /**
     * Get Status.
     *
     * @return varchar
     */
    public function getSortorder();

    /**
     * Set StartingPrice.
     */
    public function setSortorder($status);

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt();

    /**
     * Set CreatedAt.
     */

    public function setCreatedAt($createdAt);

}
