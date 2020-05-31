<?php

namespace Xigen\CsvUpload\Ui\Component\Listing\Column;

/**
 * Xigen CsvUpload CsvActions class
 */
class CsvActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    const URL_PATH_DELETE = 'xigen_csvupload/csv/delete';
    const URL_PATH_EDIT = 'xigen_csvupload/csv/edit';
    const URL_PATH_DETAILS = 'xigen_csvupload/csv/details';
    const URL_PATH_PROCESS = 'xigen_csvupload/csv/process';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['csv_id'])) {
                    $item[$this->getData('name')] = [
                        'process' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_PROCESS,
                                [
                                    'csv_id' => $item['csv_id'],
                                ]
                            ),
                            'label' => __('Insert into temp table'),
                            'confirm' => [
                                'title' => __('Insert ID ${ $.$data.csv_id }'),
                                'message' => __(
                                    'Are you sure you wan\'t to insert ID ${ $.$data.csv_id } into temp table?'
                                ),
                            ],
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'csv_id' => $item['csv_id'],
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete "${ $.$data.csv_id }"'),
                                'message' => __('Are you sure you wan\'t to delete a "${ $.$data.csv_id }" record?'),
                            ],
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
