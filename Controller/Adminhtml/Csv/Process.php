<?php


namespace Xigen\CsvUpload\Controller\Adminhtml\Csv;

/**
 * Process controller class
 */
class Process extends \Xigen\CsvUpload\Controller\Adminhtml\Csv
{
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
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
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('csv_id');
        $fileToProcess = $this->csvFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect **/
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
