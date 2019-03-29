<?php

namespace IOLabs\Schema\Upgrade;

use Doctrine\Common\Collections\ArrayCollection;
use IOLabs\Schema\AbstractYamlSchema;
use IOLabs\Schema\Hydrator\TableHydrator;
use IOLabs\Schema\Model\Column;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

abstract class AbstractUpgradeSchema extends AbstractYamlSchema implements UpgradeSchemaInterface
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
                ->getConnection();
            
            $this->syncColumns($setup, $tableName, $model->getColumns());
            
            /** @var Column $column */
            foreach ($model->getColumns() as $column) {
                $method = $table->tableColumnExists($tableName, $column->getName()) ? 'modifyColumn' : 'addColumn';
    
                $table->$method(
                    $tableName,
                    $column->getName(),
                    [
                        'type' => \constant("Magento\Framework\DB\Ddl\Table::{$column->getType()}"),
                        'size' => $column->getSize(),
                        'options' => $column->getOptions()->toArray(),
                        'comment' => $column->getComment()
                    ]
    
                );
                
            }

            foreach ($model->getIndexes() as $indexName => $field) {
                $table->addIndex($tableName, $indexName, $field);
            }
        }
    }
    
    /**
     * @param SchemaSetupInterface $setup
     * @param string               $tableName
     * @param ArrayCollection      $columns
     *
     * @return bool
     */
    public function syncColumns(SchemaSetupInterface $setup, string $tableName, ArrayCollection $columns)
    {
        $installer = $setup->getConnection();
        
        $currentColumns = [];
        array_map(function ($column) use (&$currentColumns) {
            $currentColumns[] = $column['COLUMN_NAME'];
        }, $installer->describeTable($tableName));
        
        $newColumns = [];
        array_map(function (Column $column) use (&$newColumns) {
            $newColumns[] = $column->getName();
        }, $columns->toArray());
        
        $differences = array_diff($currentColumns, $newColumns);
        if (\count($differences) < 1) {
            return true;
        }
        
        foreach ($differences as $difference) {
            $installer->dropColumn($tableName, $difference);
        }
        
        return true;
    }
    
    /**
     * @TODO: Add drop index support
     */
}
