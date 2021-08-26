<?php

namespace Xigen\CsvUpload\Controller\Adminhtml\Import;

use Magento\Framework\Exception\LocalizedException;

/**
 * Xigen CSV Import Truncate class
 */
class Truncate extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * Truncate constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        parent::__construct($context);
    }

    /**
     * Index action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $tableName = $this->resource->getTableName('xigen_csvupload_import');

        try {
            $this->connection->truncateTable($tableName);
            $this->messageManager->addSuccessMessage(__("Truncate successful"));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while modifying the Imports.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
