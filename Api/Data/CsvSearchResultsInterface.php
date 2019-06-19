<?php


namespace Xigen\CsvUpload\Api\Data;

interface CsvSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Csv list.
     * @return \Xigen\CsvUpload\Api\Data\CsvInterface[]
     */
    public function getItems();

    /**
     * Set filename list.
     * @param \Xigen\CsvUpload\Api\Data\CsvInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
