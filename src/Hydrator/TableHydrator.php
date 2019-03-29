<?php

namespace IOLabs\Schema\Hydrator;

use IOLabs\Schema\Model\Column;
use IOLabs\Schema\Model\Options;
use IOLabs\Schema\Model\Table;

/**
 * Class DefinitionsHydrator
 *
 * @package \IOLabs\Schema\Hydrator
 */
class TableHydrator
{
    public static function hydrate(string $tableName, array $tableData)
    {
        $table = new Table();
        $table
            ->setName($tableName)
            ->setIndexes($tableData['indexes'])
            ->setForeignKeys($tableData['foreign_keys']);
        
        foreach ($tableData['columns'] as $columnName => $columnData) {
            $column = new Column();
            $column
                ->setName($columnName)
                ->setType($columnData['type'])
                ->setSize($columnData['size'])
                ->setComment($columnData['comment']);
            
            $options = new Options();
            $options
                ->setIdentity($columnData['options']['identity'] ?? false)
                ->setNullable($columnData['options']['nullable'] ?? false)
                ->setPrimary($columnData['options']['primary'] ?? false)
                ->setUnsigned($columnData['options']['unsigned'] ?? false);
            
            $column->setOptions($options);
            
            $table->addColumn($column);
        }
        
        return $table;
    }
}
