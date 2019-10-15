<?php


namespace Xigen\CsvUpload\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * InstallSchema class
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $table_xigen_csvupload_csv = $setup->getConnection()->newTable($setup->getTable('xigen_csvupload_csv'));

        $table_xigen_csvupload_csv->addColumn(
            'csv_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_xigen_csvupload_csv->addColumn(
            'filename',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Filename'
        );

        $table_xigen_csvupload_csv->addColumn(
            'uploaded_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Uploaded At'
        );

        $table_xigen_csvupload_csv->addColumn(
            'processed',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'processed'
        );

        $table_xigen_csvupload_import = $setup->getConnection()->newTable($setup->getTable('xigen_csvupload_import'));

        $table_xigen_csvupload_import->addColumn(
            'import_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_xigen_csvupload_import->addColumn(
            'sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Sku'
        );

        $table_xigen_csvupload_import->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            2048,
            [],
            'Description'
        );

        $table_xigen_csvupload_import->addColumn(
            'short_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            2048,
            [],
            'Short Description'
        );

        $table_xigen_csvupload_import->addColumn(
            'fields',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            2048,
            [],
            'Fields'
        );

        $table_xigen_csvupload_import->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Created At'
        );

        $setup->getConnection()->createTable($table_xigen_csvupload_import);

        $setup->getConnection()->createTable($table_xigen_csvupload_csv);
    }
}
