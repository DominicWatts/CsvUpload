<?php

namespace Xigen\CsvUpload\Model\Data;

use Xigen\CsvUpload\Api\Data\ImportInterface;

/**
 * Xigen CsvUpload Import class
 */
class Import extends \Magento\Framework\Api\AbstractExtensibleObject implements ImportInterface
{

    /**
     * Get import_id
     * @return string|null
     */
    public function getImportId()
    {
        return $this->_get(self::IMPORT_ID);
    }

    /**
     * Set import_id
     * @param string $importId
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setImportId($importId)
    {
        return $this->setData(self::IMPORT_ID, $importId);
    }

    /**
     * Get sku
     * @return string|null
     */
    public function getSku()
    {
        return $this->_get(self::SKU);
    }

    /**
     * Set sku
     * @param string $sku
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Xigen\CsvUpload\Api\Data\ImportExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Xigen\CsvUpload\Api\Data\ImportExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Xigen\CsvUpload\Api\Data\ImportExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get fields
     * @return string|null
     */
    public function getFields()
    {
        return $this->_get(self::FIELDS);
    }

    /**
     * Set fields
     * @param string $fields
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setFields($fields)
    {
        return $this->setData(self::FIELDS, $fields);
    }

    /**
     * Get short_description
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->_get(self::SHORT_DESCRIPTION);
    }

    /**
     * Set short_description
     * @param string $shortDescription
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setShortDescription($shortDescription)
    {
        return $this->setData(self::SHORT_DESCRIPTION, $shortDescription);
    }

    /**
     * Get description
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * Set description
     * @param string $description
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
