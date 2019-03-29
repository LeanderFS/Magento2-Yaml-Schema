<?php

namespace IOLabs\Schema\Install;

use IOLabs\Schema\Exception\FileNotFoundException;
use IOLabs\Schema\Exception\FileNotReadableException;
use IOLabs\Schema\Hydrator\TableHydrator;
use IOLabs\Schema\Model\Column;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Zend_Db_Exception;

/**
 * Class AbstractInstallSchema
 *
 * @package \IOLabs\Schema\Install
 */
abstract class AbstractInstallSchema implements InstallSchemaInterface
{
    /**
     * @var string
     */
    public $definitionsFile = __DIR__ . '/config/table.yaml';
    
    /**
     * @var array
     */
    private $definitions;
    
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }
    
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
                        $column->getOptions(),
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
    
    /**
     * @return bool
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     */
    private function fileExists()
    {
        if (!file_exists($this->getDefinitionsFile())) {
            throw new FileNotFoundException('Definitions file does not exist');
        }
        
        if (!file_get_contents($this->getDefinitionsFile())) {
            throw new FileNotReadableException('Definitions file is unreadable');
        }
        
        return true;
    }
    
    /**
     * @throws FileNotReadableException
     */
    private function parseDefinitions()
    {
        try {
            $definitions = Yaml::parseFile($this->getDefinitionsFile());
            $this->setDefinitions($definitions);
        } catch (ParseException $e) {
            throw new FileNotReadableException('Definitions file is unreadable');
        }
    }
    
    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }
    
    /**
     * @param array $definitions
     *
     * @return AbstractInstallSchema
     */
    public function setDefinitions(array $definitions): AbstractInstallSchema
    {
        $this->definitions = $definitions;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDefinitionsFile(): string
    {
        return $this->definitionsFile;
    }
    
    /**
     * @param string $definitionsFile
     *
     * @return AbstractInstallSchema
     */
    public function setDefinitionsFile(string $definitionsFile): AbstractInstallSchema
    {
        $this->definitionsFile = $definitionsFile;
        
        return $this;
    }
}
