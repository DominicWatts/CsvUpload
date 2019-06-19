<?php


namespace Xigen\CsvUpload\Api\Data;

interface ImportSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Import list.
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface[]
     */
    public function getItems();

    /**
     * Set sku list.
     * @param \Xigen\CsvUpload\Api\Data\ImportInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
