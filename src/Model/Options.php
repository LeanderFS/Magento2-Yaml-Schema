<?php

namespace IOLabs\Schema\Model;

/**
 * Class Options
 *
 * @package \IOLabs\Schema\Model
 */
class Options
{
    /**
     * @var bool
     */
    private $identity;
    /**
     * @var bool
     */
    private $nullable;
    /**
     * @var bool
     */
    private $primary;
    /**
     * @var bool
     */
    private $unsigned;
    
    /**
     * @return bool
     */
    public function isIdentity(): bool
    {
        return $this->identity;
    }
    
    /**
     * @param bool $identity
     *
     * @return Options
     */
    public function setIdentity(bool $identity): Options
    {
        $this->identity = $identity;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
    
    /**
     * @param bool $nullable
     *
     * @return Options
     */
    public function setNullable(bool $nullable): Options
    {
        $this->nullable = $nullable;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->primary;
    }
    
    /**
     * @param bool $primary
     *
     * @return Options
     */
    public function setPrimary(bool $primary): Options
    {
        $this->primary = $primary;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }
    
    /**
     * @param bool $unsigned
     *
     * @return Options
     */
    public function setUnsigned(bool $unsigned): Options
    {
        $this->unsigned = $unsigned;
        
        return $this;
    }
}
