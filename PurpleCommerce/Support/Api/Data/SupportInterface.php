<?php
/**
 * PurpleCommerce_Support Support Interface.
 *
 * @category    PurpleCommerce
 *
 * @author      PurpleCommerce Software Private Limited
 */
namespace PurpleCommerce\Support\Api\Data;

interface SupportInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const TICKET_ID = 'ticket_id';
    const CUSTOMER_NAME = 'customer_name';
    const EMAIL = 'email';
    const SUBJECT = 'subject';
    const CUSTOMER_QUERY = 'customer_query';
    const CUSTOMER_ID = 'customer_id';
    const STATUS = 'status';
    const TICKET_KEY = 'ticket_key';
    const SEVERITY = 'severity';
    const ATTACHMENT = 'attachment';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getTicketId();

    /**
     * Set EntityId.
     */
    public function setTicketId($entityId);

    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getCustomerName();

    /**
     * Set Title.
     */
    public function setCustomerName($name);

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
     * Get Publish Date.
     *
     * @return varchar
     */
    public function getCustomerQuery();

    /**
     * Set PublishDate.
     */
    public function setCustomerQuery($subject);

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
    public function getTicketKey();
    
    /**
     * Set PublishDate.
     */
    public function setTicketKey($telephone);

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
     * Get Status.
     *
     * @return varchar
     */
    public function getAttachment();

    /**
     * Set StartingPrice.
     */
    public function setAttachment($attach);

    /**
     * Get Remarks.
     *
     * @return varchar
     */
    public function getSeverity();

    /**
     * Set Remarks.
     */
    public function setSeverity($remarks);

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
    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getUpdateddAt();

    /**
     * Set CreatedAt.
     */
    public function setUpdateddAt($updateddAt);
}
