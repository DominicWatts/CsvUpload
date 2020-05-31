<?php

namespace Xigen\CsvUpload\Model\ResourceModel;

/**
 * Xigen CsvUpload Import class
 */
class Import extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('xigen_csvupload_import', 'import_id');
    }
}
