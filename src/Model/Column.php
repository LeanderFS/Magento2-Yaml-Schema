<?php

namespace IOLabs\Schema\Model;

/**
 * Class Column
 *
 * @package \IOLabs\Schema\Model
 */
class Column
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $type;
    /**
     * @var mixed
     */
    private $size;
    /**
     * @var Options
     */
    private $options;
    /**
     * @var string
     */
    private $comment;
    
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
     * @return Column
     */
    public function setName(string $name): Column
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * @param string $type
     *
     * @return Column
     */
    public function setType(string $type): Column
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }
    
    /**
     * @param mixed $size
     *
     * @return Column
     */
    public function setSize($size): Column
    {
        $this->size = $size;
        
        return $this;
    }
    
    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }
    
    /**
     * @param Options $options
     *
     * @return Column
     */
    public function setOptions(Options $options): Column
    {
        $this->options = $options;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }
    
    /**
     * @param string $comment
     *
     * @return Column
     */
    public function setComment(string $comment): Column
    {
        $this->comment = $comment;
        
        return $this;
    }
}
