<?php

namespace Xigen\CsvUpload\Model\ResourceModel;

/**
 * Csv class
 */
class Csv extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('xigen_csvupload_csv', 'csv_id');
    }
}
