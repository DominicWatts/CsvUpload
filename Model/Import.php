<?php

namespace Xigen\CsvUpload\Model;

use Magento\Framework\Api\DataObjectHelper;
use Xigen\CsvUpload\Api\Data\ImportInterface;
use Xigen\CsvUpload\Api\Data\ImportInterfaceFactory;

/**
 * Xigen CsvUpload Import Model class
 */
class Import extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var ImportInterfaceFactory
     */
    protected $importDataFactory;

    /**
     * @var string
     */
    protected $_eventPrefix = 'xigen_csvupload_import';

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * Import constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ImportInterfaceFactory $importDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\Import $resource
     * @param ResourceModel\Import\Collection $resourceCollection
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ImportInterfaceFactory $importDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Xigen\CsvUpload\Model\ResourceModel\Import $resource,
        \Xigen\CsvUpload\Model\ResourceModel\Import\Collection $resourceCollection,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        array $data = []
    ) {
        $this->importDataFactory = $importDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dateTime = $dateTime;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Before save
     */
    public function beforeSave()
    {
        if ($this->isObjectNew()) {
            $this->setCreatedAt($this->dateTime->gmtDate());
        }
        return parent::beforeSave();
    }

    /**
     * Retrieve import model with import data
     * @return ImportInterface
     */
    public function getDataModel()
    {
        $importData = $this->getData();

        $importDataObject = $this->importDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $importDataObject,
            $importData,
            ImportInterface::class
        );

        return $importDataObject;
    }
}
