<?php

namespace IOLabs\Schema;

use IOLabs\Schema\Exception\FileNotFoundException;
use IOLabs\Schema\Exception\FileNotReadableException;
use Magento\Framework\Setup\SchemaSetupInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AbstractYamlSchema
 *
 * @package \IOLabs\Schema
*/
abstract class AbstractYamlSchema
{
    /**
     * @var string
     */
    private $definitionsFile;
    
    /**
     * @var array
     */
    private $definitions;
    
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }
    
    /**
     * @param SchemaSetupInterface $setup
     *
     * @return mixed
     */
    abstract public function installDefinitions(SchemaSetupInterface $setup);
    
    /**
     * @return bool
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     */
    protected function fileExists()
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
    protected function parseDefinitions()
    {
        try {
            $definitions = Yaml::parse(file_get_contents($this->getDefinitionsFile()));
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
     * @return AbstractYamlSchema
     */
    public function setDefinitions(array $definitions): AbstractYamlSchema
    {
        $this->definitions = $definitions;
        
        return $this;
    }
    
    /**
     * @return null|string
     */
    public function getDefinitionsFile()
    {
        return $this->definitionsFile;
    }
    
    /**
     * @param string $definitionsFile
     *
     * @return AbstractYamlSchema
     */
    public function setDefinitionsFile(string $definitionsFile): AbstractYamlSchema
    {
        $this->definitionsFile = $definitionsFile;
        
        return $this;
    }
}
