<?php


namespace Xigen\CsvUpload\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Import helper class
 */
class Import extends AbstractHelper
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Xigen\CsvUpload\Model\CsvFactory
     */
    private $csvFactory;

    /**
     * @var \Xigen\CsvUpload\Model\ImportFactory
     */
    private $importFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * @var \Xigen\CsvUpload\Helper\Csv
     */
    private $csvHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Xigen\CsvUpload\Model\CsvFactory $csvFactory,
        \Xigen\CsvUpload\Model\ImportFactory $importFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Xigen\CsvUpload\Helper\Csv $csvHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->logger = $logger;
        $this->csvFactory = $csvFactory;
        $this->importFactory = $importFactory;
        $this->directoryList = $directoryList;
        $this->dateTime = $dateTime;
        $this->csvHelper = $csvHelper;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * Get unprocssed file collection
     * @param string $type
     * @return \Xigen\CsvUpload\Model\ResourceModel\Import\Collection;
     */
    public function getImports($type = null, $limit = null, $offset = null)
    {
        $importCollection = $this->importFactory->create()
            ->getCollection();
        if ($type) {
            $importCollection->addFieldToFilter('type_id', ['eq' => $type]);
        }
        if ($limit) {
            $importCollection->setPageSize($limit);
        }
        if ($offset) {
            $page = floor(($offset / $limit) + 1);
            $importCollection->setCurPage($page);
        }
    
        return $importCollection;
    }

    /**
     * Get unprocssed item
     * @param string $type
     * @return \Xigen\CsvUpload\Model\Import;
     */
    public function getImport($type = null)
    {
        $importCollection = $this->getImports($type);
        if ($importCollection->getSize()) {
            return $importCollection->getFirstItem();
        }
        return null;
    }

    /**
     * Product type array
     * @return array
     */
    public function getTypeArray()
    {
        $array = [
            \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
            \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE,
            \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
            \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE,
            \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE,
            \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE
        ];
        return $array;
    }

    /**
     * Get array of import collections
     * @param string $productType
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getImportsCollectionArray($productType = null, $limit = null, $offset = null)
    {
        $array = [];

        $typeArray = $this->getTypeArray();
        if ($productType) {
            $typeArray = [$productType];
        }

        foreach ($typeArray as $type) {
            $collection = $this->getImports($type, $limit, $offset);
            if ($collection && $collection->getSize() > 0) {
                $array[$type] = $collection;
            }
        }
        if (empty($array)) {
            return false;
        }
        return $array;
    }

    /**
     * Load file by Id
     * @param int $importId
     * \Xigen\CsvUpload\Model\Import
     */
    public function loadImportById($importId)
    {
        return $this->importFactory->create->load($importId);
    }

    /**
     * Parse import row by ID
     * @param int $importId
     * @return array
     */
    public function parseImportRowById($importId)
    {
        $array = [];
        if ($import = $this->loadImportById($importId)) {
            $array = $this->parseImport($import);
        }
        return $array;
    }

    /**
     * Parse import row
     * @param \Xigen\CsvUpload\Model\Import $import
     * @return void
     */
    public function parseImport(\Xigen\CsvUpload\Model\Import $import)
    {
        $array = [];
        if ($import && $import->getId()) {
            $data = $import->getData();
            $removes = $this->csvHelper->getTopLevelIgnoreArray();
            foreach ($removes as $remove) {
                if (isset($data[$remove])) {
                    unset($data[$remove]);
                }
            }
            $fields = [];
            if (isset($data['fields'])) {
                $fields = $this->serializer->unserialize($data['fields']);
                unset($data['fields']);
            }
            $array = array_merge($data, $fields);
        }
        return $array;
    }
}
