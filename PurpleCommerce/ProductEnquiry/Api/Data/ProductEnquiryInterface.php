<?php
/**
 * PurpleCommerce_ProductEnquiry ProductEnquiry Interface.
 *
 * @category    PurpleCommerce
 *
 * @author      PurpleCommerce Software Private Limited
 */
namespace PurpleCommerce\ProductEnquiry\Api\Data;

interface ProductEnquiryInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const NAME = 'name';
    const EMAIL = 'email';
    const SUBJECT = 'subject';
    const MESSAGE = 'message';
    const TELEPHONE = 'telephone';
    const STATUS = 'status';
    const REMARKS = 'remarks';
    const CREATED_AT = 'created_at';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId);

    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getName();

    /**
     * Set Title.
     */
    public function setName($name);

    /**
     * Get Content.
     *
     * @return varchar
     */
    public function getEmail();

    /**
     * Set Content.
     */
    public function setEmail($email);

    /**
     * Get Publish Date.
     *
     * @return varchar
     */
    public function getSubject();

    /**
     * Set PublishDate.
     */
    public function setSubject($subject);

    /**
     * Set PublishDate.
     *
     * @return varchar
     */
    public function getMessage();
    /**
     * Set PublishDate.
     */
    public function setMessage($message);
    /**
     * Set PublishDate.
     */
    public function getTelephone();
    
    /**
     * Set PublishDate.
     */
    public function setTelephone($telephone);

    /**
     * Get Status.
     *
     * @return varchar
     */
    public function getStatus();

    /**
     * Set StartingPrice.
     */
    public function setStatus($status);

    /**
     * Get Remarks.
     *
     * @return varchar
     */
    public function getRemarks();

    /**
     * Set Remarks.
     */
    public function setRemarks($remarks);

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
