<?php
namespace Tinyga\ImageOptimizerClient\OptimizationRequest;


use Tinyga\ImageOptimizerClient\ImageOptimizerClientException;

class FileSimpleOptimizationRequest extends AbstractOptimizationRequest
{
    /**
     * @var \SplFileInfo $file
     */
    protected $file;

    /**
     * @param \SplFileInfo|null $file
     * @param int|null $quality
     * @param array|null $keep_metadata
     * @throws ImageOptimizerClientException
     */
    public function __construct(\SplFileInfo $file = null, $quality = null, array $keep_metadata = null)
    {
        parent::__construct($quality, $keep_metadata);
        $file && $this->setFile($file);
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \SplFileInfo $file
     */
    public function setFile(\SplFileInfo $file)
    {
        if(!$this->file->isReadable() || !$this->file->isFile()){
            throw new \InvalidArgumentException("File '{$file->getPathname()}' does not exist or is not readable");
        }
        $this->file = $file;
    }


    /**
     * @return string
     */
    public function getImageFileName()
    {
        return $this->file
            ? $this->file->getBasename()
            : '';
    }

    /**
     * @return string
     */
    public function getImageContent()
    {
        if(!$this->file){
            return '';
        }
        return (string)file_get_contents($this->file->getRealPath());
    }
}