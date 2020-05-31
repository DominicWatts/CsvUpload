<?php

namespace Xigen\CsvUpload\Controller\Adminhtml\Import;

use Magento\Framework\Exception\LocalizedException;

/**
 * Xigen CsvUpload Save controller class
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Xigen\CsvUpload\Model\ImportFactory
     */
    private $importFactory;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Xigen\CsvUpload\Model\ImportFactory $importFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Xigen\CsvUpload\Model\ImportFactory $importFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->importFactory = $importFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('import_id');

            $model = $this->importFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Import no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Import.'));
                $this->dataPersistor->clear('xigen_csvupload_import');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['import_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Import.'));
            }

            $this->dataPersistor->set('xigen_csvupload_import', $data);
            return $resultRedirect->setPath('*/*/edit', ['import_id' => $this->getRequest()->getParam('import_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
