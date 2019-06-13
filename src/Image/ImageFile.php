<?php
namespace Tinyga\ImageOptimizer\Image;

use SplFileInfo;

class ImageFile extends Image
{
    /**
     * @var string|SplFileInfo
     */
    protected $file;

    /**
     * @param string|SplFileInfo $url
     * @param null|string $file_name
     */
    function __construct($url, $file_name = null)
    {
        $this->setFile($url);
        if(trim($file_name) === ''){
            $file_name = basename($this->getFilePath());
        }
        parent::__construct($file_name);
    }

    /**
     * @return SplFileInfo|string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param SplFileInfo|string $file
     */
    protected function setFile($file)
    {
        if(
            (!is_string($file) || !$file) &&
            !$file instanceof SplFileInfo
        ){
            throw new \InvalidArgumentException("Invalid file");
        }

        $this->file = $file;
    }

    public function getFilePath()
    {
        if($this->file === null){
            throw new \RuntimeException("File is not defined");
        }

        if($this->file instanceof SplFileInfo){
            $path = $this->file->getRealPath();
        } else {
            $path = $this->file;
        }

        return $path;
    }

    /**
     * @return string
     */
    function getContent()
    {
        $path = $this->getFilePath();
        if(!is_file($path) || !is_readable($path)){
            throw new \RuntimeException("File '{$path}' does not exist or is not readable");
        }

        $content = @file_get_contents($this->file);
        if($content === false){
            throw new \RuntimeException("Failed to read file '{$path}' content");
        }

        return $content;
    }
}