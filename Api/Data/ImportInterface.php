<?php

namespace Xigen\CsvUpload\Api\Data;

/**
 * Interface ImportInterface
 */
interface ImportInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const CREATED_AT = 'created_at';
    const FIELDS = 'fields';
    const SKU = 'sku';
    const IMPORT_ID = 'import_id';
    const DESCRIPTION = 'description';
    const SHORT_DESCRIPTION = 'short_description';

    /**
     * Get import_id
     * @return string|null
     */
    public function getImportId();

    /**
     * Set import_id
     * @param string $importId
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setImportId($importId);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setSku($sku);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Xigen\CsvUpload\Api\Data\ImportExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Xigen\CsvUpload\Api\Data\ImportExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Xigen\CsvUpload\Api\Data\ImportExtensionInterface $extensionAttributes
    );

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setDescription($description);

    /**
     * Get short_description
     * @return string|null
     */
    public function getShortDescription();

    /**
     * Set short_description
     * @param string $shortDescription
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */

    /**
     * Get fields
     * @return string|null
     */
    public function getFields();

    /**
     * Set fields
     * @param string $fields
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setFields($fields);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setCreatedAt($createdAt);
}
