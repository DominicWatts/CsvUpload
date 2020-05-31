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
use Xigen\CsvUpload\Api\Data\ImportInterfaceFactory;
use Xigen\CsvUpload\Api\Data\ImportSearchResultsInterfaceFactory;
use Xigen\CsvUpload\Api\ImportRepositoryInterface;
use Xigen\CsvUpload\Model\ResourceModel\Import as ResourceImport;
use Xigen\CsvUpload\Model\ResourceModel\Import\CollectionFactory as ImportCollectionFactory;

/**
 * ImportRepository class
 */
class ImportRepository implements ImportRepositoryInterface
{
    /**
     * @var ImportCollectionFactory
     */
    protected $importCollectionFactory;

    /**
     * @var ResourceImport
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
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var ImportInterfaceFactory
     */
    protected $dataImportFactory;

    /**
     * @var ImportSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var ImportFactory
     */
    protected $importFactory;

    /**
     * @param ResourceImport $resource
     * @param ImportFactory $importFactory
     * @param ImportInterfaceFactory $dataImportFactory
     * @param ImportCollectionFactory $importCollectionFactory
     * @param ImportSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceImport $resource,
        ImportFactory $importFactory,
        ImportInterfaceFactory $dataImportFactory,
        ImportCollectionFactory $importCollectionFactory,
        ImportSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->importFactory = $importFactory;
        $this->importCollectionFactory = $importCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataImportFactory = $dataImportFactory;
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
        \Xigen\CsvUpload\Api\Data\ImportInterface $import
    ) {
        /* if (empty($import->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $import->setStoreId($storeId);
        } */

        $importData = $this->extensibleDataObjectConverter->toNestedArray(
            $import,
            [],
            \Xigen\CsvUpload\Api\Data\ImportInterface::class
        );

        $importModel = $this->importFactory->create()->setData($importData);

        try {
            $this->resource->save($importModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the import: %1',
                $exception->getMessage()
            ));
        }
        return $importModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($importId)
    {
        $import = $this->importFactory->create();
        $this->resource->load($import, $importId);
        if (!$import->getId()) {
            throw new NoSuchEntityException(__('Import with id "%1" does not exist.', $importId));
        }
        return $import->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->importCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Xigen\CsvUpload\Api\Data\ImportInterface::class
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
        \Xigen\CsvUpload\Api\Data\ImportInterface $import
    ) {
        try {
            $importModel = $this->importFactory->create();
            $this->resource->load($importModel, $import->getImportId());
            $this->resource->delete($importModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Import: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($importId)
    {
        return $this->delete($this->getById($importId));
    }
}
