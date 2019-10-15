<?php


namespace Xigen\CsvUpload\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface CsvRepositoryInterface
 * @package Xigen\CsvUpload\Api
 */
interface CsvRepositoryInterface
{

    /**
     * Save Csv
     * @param \Xigen\CsvUpload\Api\Data\CsvInterface $csv
     * @return \Xigen\CsvUpload\Api\Data\CsvInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Xigen\CsvUpload\Api\Data\CsvInterface $csv
    );

    /**
     * Retrieve Csv
     * @param string $csvId
     * @return \Xigen\CsvUpload\Api\Data\CsvInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($csvId);

    /**
     * Retrieve Csv matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Xigen\CsvUpload\Api\Data\CsvSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Csv
     * @param \Xigen\CsvUpload\Api\Data\CsvInterface $csv
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Xigen\CsvUpload\Api\Data\CsvInterface $csv
    );

    /**
     * Delete Csv by ID
     * @param string $csvId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($csvId);
}
