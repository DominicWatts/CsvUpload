<?php

namespace Xigen\CsvUpload\Controller\Adminhtml\Csv;

/**
 * Xigen CsvUpload Mass-Status Controller.
 */
class MassStatus extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Xigen_CsvUpload::Csv';

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    private $filter;

    /**
     * @var \Xigen\CsvUpload\Model\CsvFactory
     */
    private $csvFactory;

    /**
     * MassStatus constructor
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Xigen\CsvUpload\Model\CsvFactory $csvFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Xigen\CsvUpload\Model\CsvFactory $csvFactory
    ) {
        $this->filter = $filter;
        $this->csvFactory = $csvFactory;
        parent::__construct($context);
    }

    /**
     * Execute action.
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $ids = $this->getRequest()->getPost('selected');
        $processed = $this->getRequest()->getParam('processed');
        if ($ids) {
            $collection = $this->csvFactory->create()
                ->getCollection()
                ->addFieldToFilter('csv_id', ['in' => $ids]);
            $collectionSize = $collection->getSize();
            $updatedItems = 0;
            foreach ($collection as $item) {
                try {
                    $item->setProcessed($processed);
                    $item->save();
                    $updatedItems++;
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
            if ($updatedItems != 0) {
                if ($collectionSize != $updatedItems) {
                    $this->messageManager->addErrorMessage(
                        __('Failed to update %1 csv file(s).', $collectionSize - $updatedItems)
                    );
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 csv file(s) have been updated.', $updatedItems)
                );
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
