<?php


namespace Xigen\CsvUpload\Controller\Adminhtml\Csv;

/**
 * Edit Controller class
 */
class Edit extends \Xigen\CsvUpload\Controller\Adminhtml\Csv
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
        \Xigen\CsvUpload\Model\CsvFactory $csvFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->csvFactory = $csvFactory;
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
        $model = $this->csvFactory->create();
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Csv no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('xigen_csvupload_csv', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Csv') : __('New Csv'),
            $id ? __('Edit Csv') : __('New Csv')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Csvs'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Csv %1', $model->getId()) : __('New Csv'));
        return $resultPage;
    }
}
