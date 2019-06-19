<?php


namespace Xigen\CsvUpload\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

/**
 * Process controller class
 */
class Process extends \Magento\Backend\App\Action
{

    /**
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Session\Generic $generic,
        \Psr\Log\LoggerInterface $logger,
        \Xigen\CsvUpload\Model\CsvFactory $csvFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->generic = $generic;
        $this->csvFactory = $csvFactory;
        
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = [];

        try {
            $target = $this->mediaDirectory->getAbsolutePath('csv/' . $this->generic->getSessionId() . '/');
                
            $uploader = $this->uploaderFactory->create(['fileId' => 'import_csv_file']);
                                    
            $fileType = $uploader->getFileExtension();
            $newFileName = uniqid() . '.' . $fileType;

            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(true);

            $result = $uploader->save($target, $newFileName);

            if ($result['file']) {
                $mediaUrl = $this->storeManager->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $src = $mediaUrl . 'csv/' . $this->generic->getSessionId(). $newFileName;

                $error = false;
                $message = __("File has been successfully uploaded");

                $data = [
                    'filename' => $newFileName,
                    'path' => $mediaUrl . 'csv/' . $this->generic->getSessionId() . '/' . $newFileName,
                ];

                $file = $this->csvFactory->create();
                $file->setFilename($data['path']);
                $file->save();

                $this->messageManager->addSuccess(__($message));
            }
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            $this->messageManager->addError(__($message));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/csv/');
        return $resultRedirect;
    }
}
