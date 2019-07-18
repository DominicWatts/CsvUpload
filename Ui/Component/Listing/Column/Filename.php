<?php

namespace Xigen\CsvUpload\Ui\Component\Listing\Column;

/**
 * Filename renderer class
 */
class Filename extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Filename constructor.
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

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
