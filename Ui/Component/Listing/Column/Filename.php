<?php

namespace Xigen\CsvUpload\Ui\Component\Listing\Column;

/**
 * Filename renderer class
 */
class Filename extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param array $dataSource
     * @return void
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['filename'])) {
                    $item['filename'] = __('<a href="%1" target=_"blank">%1</a>', $item['filename']);
                }
            }
        }

        return $dataSource;
    }
}
