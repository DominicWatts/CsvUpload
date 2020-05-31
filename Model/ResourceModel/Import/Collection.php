<?php

namespace Xigen\CsvUpload\Model\ResourceModel\Import;

/**
 * Xigen CsvUpload Collection class
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Xigen\CsvUpload\Model\Import::class,
            \Xigen\CsvUpload\Model\ResourceModel\Import::class
        );
    }
}
