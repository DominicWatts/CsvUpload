<?php


namespace Xigen\CsvUpload\Model\ResourceModel\Csv;

/**
 * Collection class
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
            \Xigen\CsvUpload\Model\Csv::class,
            \Xigen\CsvUpload\Model\ResourceModel\Csv::class
        );
    }
}
