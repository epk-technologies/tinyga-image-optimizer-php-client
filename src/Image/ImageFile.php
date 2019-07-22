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
     * @param SplFileInfo $file
     * @param string|null $file_name
     */
    function __construct(SplFileInfo $file, $file_name = null)
    {
        $this->setFile($file);
        if($file_name === null){
            $file_name = $file->getBasename();
        }
        parent::__construct($file_name);
    }

    /**
     * @return SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param SplFileInfo $file
     */
    protected function setFile(SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    function getImageContent()
    {
        if(!$this->file->isFile() || !$this->file->isReadable()){
            throw new \InvalidArgumentException("File '{$this->file->getPathname()}' does not exist or is not readable");
        }

        $path = $this->file->getRealPath();
        $content = @file_get_contents($this->file);
        if($content === false){
            throw new \RuntimeException("Failed to read file '{$path}' content");
        }

        return $content;
    }
}
