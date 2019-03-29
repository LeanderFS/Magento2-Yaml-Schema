<?php

namespace IOLabs\Schema\Install;

use IOLabs\Schema\AbstractYamlSchema;
use IOLabs\Schema\Hydrator\TableHydrator;
use IOLabs\Schema\Model\Column;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

/**
 * Class AbstractInstallSchema
 *
 * @package \IOLabs\Schema\Install
 */
abstract class AbstractInstallSchema extends AbstractYamlSchema implements InstallSchemaInterface
{
    public function installDefinitions(SchemaSetupInterface $setup)
    {
        $installer = $setup;
        
        $this->fileExists();
        $this->parseDefinitions();
        
        $tables = $this->getDefinitions();
    
        foreach ($tables as $tableName => $tableData) {
            $model = TableHydrator::hydrate($tableName, $tableData);
            $table = $installer
                ->getConnection()
                ->newTable(
                    $installer->getTable($tableName)
                );
        
            /** @var Column $column */
            foreach ($model->getColumns() as $column) {
                try {
                    $table->addColumn(
                        $column->getName(),
                        \constant("Magento\Framework\DB\Ddl\Table::{$column->getType()}"),
                        $column->getSize(),
                        $column->getOptions()->toArray(),
                        $column->getComment()
                    );
                } catch (Zend_Db_Exception $e) {
                    $this->logger->error($e->getMessage(), $e);
                    continue;
                }
            }
            
            foreach ($model->getIndexes() as $indexName => $field) {
                try {
                    $table->addIndex($indexName, $field);
                } catch (Zend_Db_Exception $e) {
                    $this->logger->error($e->getMessage(), $e);
                    continue;
                }
            }
            
            foreach ($model->getForeignKeys() as $fkName => $fkData) {
                try {
                    $table->addForeignKey($fkName, $fkData['column'], $fkData['refTable'], $fkData['refColumn']);
                } catch (Zend_Db_Exception $e) {
                    $this->logger->error($e->getMessage(), $e);
                    continue;
                }
            }
        
            try {
                $installer->getConnection()->createTable($table);
            } catch (Zend_Db_Exception $e) {
                $this->logger->error($e->getMessage(), $e);
                continue;
            }
        }
    }
}
