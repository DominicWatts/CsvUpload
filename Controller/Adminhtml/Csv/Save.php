<?php


namespace Xigen\CsvUpload\Controller\Adminhtml\Csv;

use Magento\Framework\Exception\LocalizedException;

/**
 * Save controller class
 */
class Save extends \Magento\Backend\App\Action
{
    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Xigen\CsvUpload\Model\CsvFactory $csvFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->csvFactory = $csvFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('csv_id');
        
            $model = $this->csvFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Csv no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Csv.'));
                $this->dataPersistor->clear('xigen_csvupload_csv');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['csv_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Csv.'));
            }
        
            $this->dataPersistor->set('xigen_csvupload_csv', $data);
            return $resultRedirect->setPath('*/*/edit', ['csv_id' => $this->getRequest()->getParam('csv_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
