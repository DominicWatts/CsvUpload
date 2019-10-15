<?php


namespace Xigen\CsvUpload\Block\Adminhtml\Import\Edit;

use Magento\Backend\Block\Widget\Context;

/**
 * GenericButton abstract class
 */
abstract class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Return model ID
     * @return int|null
     */
    public function getModelId()
    {
        return $this->context->getRequest()->getParam('import_id');
    }

    /**
     * Generate url by route and parameters
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
