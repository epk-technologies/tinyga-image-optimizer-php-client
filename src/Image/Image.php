<?php
namespace Tinyga\ImageOptimizer\Image;

abstract class Image implements ImageInterface
{
    /**
     * @var string
     */
    protected $file_name;

    /**
     * @var ImageParameters
     */
    protected $image_parameters;

    /**
     * @param string $file_name
     */
    public function __construct($file_name)
    {
        $this->file_name = trim($file_name);
        if($this->file_name === ''){
            throw new \InvalidArgumentException("File name is not defined");
        }
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return (string)$this->file_name;
    }

    /**
     * @return ImageParameters
     */
    function getImageParameters()
    {
        if(!$this->image_parameters){
            $this->image_parameters = ImageParametersResolver::resolveImageParameters($this);
        }
        return $this->image_parameters;
    }


    /**
     * @return string
     */
    function __toString()
    {
        return $this->getContent();
    }
}