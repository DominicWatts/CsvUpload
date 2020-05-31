<?php

namespace Xigen\CsvUpload\Controller\Adminhtml\Csv;

/**
 * Xigen CsvUpload Process controller class
 */
class Process extends \Xigen\CsvUpload\Controller\Adminhtml\Csv
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

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
     * @var \Xigen\CsvUpload\Helper\Csv
     */
    private $csvHelper;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * Process constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Xigen\CsvUpload\Model\CsvFactory $csvFactory
     * @param \Xigen\CsvUpload\Model\ImportFactory $importFactory
     * @param \Xigen\CsvUpload\Helper\Csv $csvHelper
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\File\Csv $csvProcessor,
        \Xigen\CsvUpload\Model\CsvFactory $csvFactory,
        \Xigen\CsvUpload\Model\ImportFactory $importFactory,
        \Xigen\CsvUpload\Helper\Csv $csvHelper,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
        $this->csvProcessor = $csvProcessor;
        $this->csvFactory = $csvFactory;
        $this->importFactory = $importFactory;
        $this->csvHelper = $csvHelper;
        $this->directoryList = $directoryList;
        $this->dateTime = $dateTime;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('csv_id');
        $fileToProcess = $this->csvFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect * */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $fileToProcess->load($id);
            if (!$fileToProcess->getId()) {
                $this->messageManager->addErrorMessage(__('This Csv no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('xigen_csvupload_csv', $fileToProcess);

        $path = $this->csvHelper->getFilepath();
        $file = $this->csvHelper->getFilename($fileToProcess->getFilename());

        $readCsv = $this->csvProcessor->getData($path . $file);

        if (isset($readCsv[0])) {
            $headers = $readCsv[0];
            unset($readCsv[0]);
        }

        foreach ($readCsv as $readRow) {
            $this->csvHelper->storeInTempTable($headers, $readRow);
        }

        $this->csvHelper->setCsvFileProcessToId($fileToProcess->getId(), 1);
        $this->messageManager->addSuccessMessage(__('CSV file loaded into temp table.'));

        return $resultRedirect->setPath('*/import/');
    }
}
