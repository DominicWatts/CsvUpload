<?php


namespace Xigen\CsvUpload\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ImportRepositoryInterface
 * @package Xigen\CsvUpload\Api
 */
interface ImportRepositoryInterface
{

    /**
     * Save Import
     * @param \Xigen\CsvUpload\Api\Data\ImportInterface $import
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Xigen\CsvUpload\Api\Data\ImportInterface $import
    );

    /**
     * Retrieve Import
     * @param string $importId
     * @return \Xigen\CsvUpload\Api\Data\ImportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($importId);

    /**
     * Retrieve Import matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Xigen\CsvUpload\Api\Data\ImportSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Import
     * @param \Xigen\CsvUpload\Api\Data\ImportInterface $import
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Xigen\CsvUpload\Api\Data\ImportInterface $import
    );

    /**
     * Delete Import by ID
     * @param string $importId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($importId);
}
