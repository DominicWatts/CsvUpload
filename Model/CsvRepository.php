<?php

namespace Xigen\CsvUpload\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Xigen\CsvUpload\Api\CsvRepositoryInterface;
use Xigen\CsvUpload\Api\Data\CsvInterfaceFactory;
use Xigen\CsvUpload\Api\Data\CsvSearchResultsInterfaceFactory;
use Xigen\CsvUpload\Model\ResourceModel\Csv as ResourceCsv;
use Xigen\CsvUpload\Model\ResourceModel\Csv\CollectionFactory as CsvCollectionFactory;

/**
 * Xigen CsvUpload CsvRepository class
 */
class CsvRepository implements CsvRepositoryInterface
{
    /**
     * @var ResourceCsv
     */
    protected $resource;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CsvInterfaceFactory
     */
    protected $dataCsvFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var CsvFactory
     */
    protected $csvFactory;

    /**
     * @var CsvCollectionFactory
     */
    protected $csvCollectionFactory;

    /**
     * @var CsvSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @param ResourceCsv $resource
     * @param CsvFactory $csvFactory
     * @param CsvInterfaceFactory $dataCsvFactory
     * @param CsvCollectionFactory $csvCollectionFactory
     * @param CsvSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceCsv $resource,
        CsvFactory $csvFactory,
        CsvInterfaceFactory $dataCsvFactory,
        CsvCollectionFactory $csvCollectionFactory,
        CsvSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->csvFactory = $csvFactory;
        $this->csvCollectionFactory = $csvCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCsvFactory = $dataCsvFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Xigen\CsvUpload\Api\Data\CsvInterface $csv
    ) {
        $csvData = $this->extensibleDataObjectConverter->toNestedArray(
            $csv,
            [],
            \Xigen\CsvUpload\Api\Data\CsvInterface::class
        );

        $csvModel = $this->csvFactory->create()->setData($csvData);

        try {
            $this->resource->save($csvModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the csv: %1',
                $exception->getMessage()
            ));
        }
        return $csvModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($csvId)
    {
        $csv = $this->csvFactory->create();
        $this->resource->load($csv, $csvId);
        if (!$csv->getId()) {
            throw new NoSuchEntityException(__('Csv with id "%1" does not exist.', $csvId));
        }
        return $csv->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->csvCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Xigen\CsvUpload\Api\Data\CsvInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Xigen\CsvUpload\Api\Data\CsvInterface $csv
    ) {
        try {
            $csvModel = $this->csvFactory->create();
            $this->resource->load($csvModel, $csv->getCsvId());
            $this->resource->delete($csvModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Csv: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($csvId)
    {
        return $this->delete($this->getById($csvId));
    }
}
