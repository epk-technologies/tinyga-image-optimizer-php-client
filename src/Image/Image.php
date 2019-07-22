<?php
namespace Tinyga\ImageOptimizer\Image;

abstract class Image implements ImageInterface
{
    /**
     * @var ImageParameters
     */
    protected $image_parameters;

    /**
     * @var string
     */
    protected $file_name = '';

    /**
     * @param string $file_name
     */
    public function __construct($file_name)
    {
        $this->setFileName($file_name);
    }


    /**
     * @return ImageParameters
     */
    public function getImageParameters()
    {
        if(!$this->image_parameters){
            $this->image_parameters = ImageParametersResolver::resolveImageParameters($this);
        }
        return $this->image_parameters;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @param string $file_name
     */
    public function setFileName($file_name)
    {
        $this->file_name = trim($file_name);
    }



    function __toString()
    {
        return $this->getImageContent();
    }
}
