<?php


namespace Xigen\CsvUpload\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Csv extends AbstractHelper
{
    private $logger;
    private $csvProcessor;
    private $csvFactory;
    private $directoryList;
    private $dateTime;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\File\Csv $csvProcessor,
        \Xigen\CsvUpload\Model\CsvFactory $csvFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->logger = $logger;
        $this->csvProcessor = $csvProcessor;
        $this->csvFactory = $csvFactory;
        $this->directoryList = $directoryList;
        $this->dateTime = $dateTime;
        parent::__construct($context);
    }

    /**
     * Get unprocssed file collection
     * @return \Xigen\Data\Model\ResourceModel\Csv\Collection;
     */
    public function getUnprocessedFiles()
    {
        $csvCollection = $this->csvFactory->create()
            ->getCollection()
            ->addFieldToFilter('processed', array('eq' => '0'));
        return $csvCollection;
    }

    /**
     * Get unprocssed item
     * @return \Xigen\Data\Model\Csv;
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
     * \Xigen\Data\Model\Csv
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
            throw new \Exception('Url "' . $url . '" is blank');
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
            throw new \Exception(__("Problem setting file ID $csvId as Status ID $processId"));
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
}
