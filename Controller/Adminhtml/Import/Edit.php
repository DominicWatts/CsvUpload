<?php

namespace Xigen\CsvUpload\Controller\Adminhtml\Import;

/**
 * Xigen CSV Import Edit controller class
 */
class Edit extends \Xigen\CsvUpload\Controller\Adminhtml\Import
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Xigen\CsvUpload\Model\ImportFactory
     */
    private $importFactory;

    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Xigen\CsvUpload\Model\ImportFactory $importFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Xigen\CsvUpload\Model\ImportFactory $importFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->importFactory = $importFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('import_id');
        $model = $this->importFactory->create();

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Import no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('xigen_csvupload_import', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Import') : __('New Import'),
            $id ? __('Edit Import') : __('New Import')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Imports'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit Import %1', $model->getId()) : __('New Import')
        );
        return $resultPage;
    }
}
