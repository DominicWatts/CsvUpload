<?php


namespace Xigen\CsvUpload\Model;

use Magento\Framework\Api\DataObjectHelper;
use Xigen\CsvUpload\Api\Data\CsvInterface;
use Xigen\CsvUpload\Api\Data\CsvInterfaceFactory;

/**
 * Csv class
 */
class Csv extends \Magento\Framework\Model\AbstractModel
{
    protected $_eventPrefix = 'xigen_csvupload_csv';
    protected $dataObjectHelper;
    protected $csvDataFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param CsvInterfaceFactory $csvDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Xigen\CsvUpload\Model\ResourceModel\Csv $resource
     * @param \Xigen\CsvUpload\Model\ResourceModel\Csv\Collection $resourceCollection
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        CsvInterfaceFactory $csvDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Xigen\CsvUpload\Model\ResourceModel\Csv $resource,
        \Xigen\CsvUpload\Model\ResourceModel\Csv\Collection $resourceCollection,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        array $data = []
    ) {
        $this->csvDataFactory = $csvDataFactory;
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
            $this->setProcessed(0);
            $this->setUploadedAt($this->dateTime->gmtDate());
        }
        return parent::beforeSave();
    }

    /**
     * Retrieve csv model with csv data
     * @return CsvInterface
     */
    public function getDataModel()
    {
        $csvData = $this->getData();
        
        $csvDataObject = $this->csvDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $csvDataObject,
            $csvData,
            CsvInterface::class
        );
        
        return $csvDataObject;
    }
}
