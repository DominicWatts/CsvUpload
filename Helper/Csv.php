<?php


namespace Xigen\CsvUpload\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Csv helper class
 */
class Csv extends AbstractHelper
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\File\Csv
     */
    private $csvProcessor;

    /**
     * @var \Xigen\CsvUpload\Model\CsvFactory
     */
    private $csvFactory;

    /**
     * @var \Xigen\CsvUpload\Model\ImportFactory
     */
    private $importFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\File\Csv $csvProcessor,
        \Xigen\CsvUpload\Model\CsvFactory $csvFactory,
        \Xigen\CsvUpload\Model\ImportFactory $importFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->logger = $logger;
        $this->csvProcessor = $csvProcessor;
        $this->csvFactory = $csvFactory;
        $this->importFactory = $importFactory;
        $this->directoryList = $directoryList;
        $this->dateTime = $dateTime;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * Get unprocssed file collection
     * @return \Xigen\CsvUpload\Model\ResourceModel\Csv\Collection;
     */
    public function getUnprocessedFiles()
    {
        $csvCollection = $this->csvFactory->create()
            ->getCollection()
            ->addFieldToFilter('processed', ['eq' => '0']);
        return $csvCollection;
    }

    /**
     * Get unprocssed item
     * @return \Xigen\CsvUpload\Model\Csv;
     */
    public function getUnprocessedFile()
    {
        $csvCollection = $this->getUnprocessedFiles();
        if ($csvCollection->getSize()) {
            return $csvCollection->getFirstItem();
        }
        return null;
    }

    /**
     * Load file by Id
     * @param int $fileId
     * \Xigen\CsvUpload\Model\Csv
     */
    public function loadFileById($fileId)
    {
        return $this->csvFactory->create()->load($fileId);
    }

    /**
     * Get clean filename
     * @param string $string
     * @return string
     */
    public function getFilename($url = null)
    {
        if (!$url) {
            throw new \LocalizedException('Url "' . $url . '" is blank');
        }
        $removePrefix = explode('//', $url);
        $parts = explode('/', $removePrefix[1]);
        krsort($parts);
        $parts = array_values($parts);
        return '/' . $parts[1] . '/' . basename($url);
    }

    /**
     * Get file path
     * @param string $string
     * @return string
     */
    public function getFilepath()
    {
        return $this->directoryList->getPath('media') . '/csv';
    }

    /**
     * Update process flag
     * @param int $csvId
     * @param int $processId
     * @return void
     */
    public function setCsvFileProcessToId($csvId = null, $processId = null)
    {
        if (!$csvId || !$processId) {
            throw new \LocalizedException(__("Problem setting file ID $csvId as Status ID $processId"));
        }

        $fileToProcess = $this->csvFactory->create()->load($csvId);
        $fileToProcess->setProcessed($processId);
        try {
            $fileToProcess->save();
            return true;
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return false;
    }

    /**
     * Read CSV row, manipulate and store in temp table
     * @param array $headers
     * @param array $readRow
     * @return bool
     */
    public function storeInTempTable($headers = [], $readRow = [])
    {
        if (empty($headers) || empty($readRow)) {
            throw new \LocalizedException(__("Problem loading data"));
        }
        $insertArray = [];
        foreach ($readRow as $key => $readItem) {
            $insertArray[$headers[$key]] = $readItem;
        }

        $import = $this->importFactory->create();
        
        $topLevelArray = $this->getTopLevelArray();
        foreach ($topLevelArray as $topLevelItem) {
            if (isset($insertArray[$topLevelItem])) {
                $import->setData($topLevelItem, $insertArray[$topLevelItem]);
                unset($insertArray[$topLevelItem]);
            }
        }

        $import->setFields($this->serializer->serialize($insertArray));

        try {
            $import->save();
            return true;
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return false;
    }

    /**
     * Define top level array
     * @return array
     */
    public function getTopLevelArray()
    {
        return ['sku', 'description', 'short_description'];
    }

    /**
     * Define top level ignore array
     * @return array
     */
    public function getTopLevelIgnoreArray()
    {
        return ['import_id', 'created_at', 'updated_at'];
    }
}
