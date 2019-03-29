<?php

namespace IOLabs\Schema\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class SchemaDefinitionModel
 *
 * @package \IOLabs\Schema\Model
 */
class Table
{
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var array
     */
    private $indexes;
    
    /**
     * @var array
     */
    private $columns;
    
    /**
     * @var array
     */
    private $foreignKeys;
    
    public function __construct()
    {
        $this->columns = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     *
     * @return Table
     */
    public function setName(string $name): Table
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getIndexes(): array
    {
        return $this->indexes;
    }
    
    /**
     * @param array $indexes
     *
     * @return Table
     */
    public function setIndexes(array $indexes): Table
    {
        if ($indexes !== null) {
            $this->indexes = $indexes;
        } else {
            $this->indexes = [];
        }
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }
    
    /**
     * @param array $foreignKeys
     *
     * @return Table
     */
    public function setForeignKeys(array $foreignKeys): Table
    {
        if ($foreignKeys !== null) {
            $this->foreignKeys = $foreignKeys;
        } else {
            $this->foreignKeys = [];
        }
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getColumns(): ArrayCollection
    {
        return $this->columns;
    }
    
    /**
     * @param array $columns
     *
     * @return Table
     */
    public function setColumns(array $columns): Table
    {
        $this->columns = $columns;
        
        return $this;
    }
    
    public function addColumn(Column $column): Table
    {
        if (!$this->columns->contains($column)) {
            $this->columns->add($column);
        }
        
        return $this;
    }
}
