<?php


namespace Xigen\CsvUpload\Controller\Adminhtml\Csv;

/**
 * Dlete controller class
 */
class Delete extends \Xigen\CsvUpload\Controller\Adminhtml\Csv
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('csv_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Xigen\CsvUpload\Model\Csv::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Csv.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['csv_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Csv to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
