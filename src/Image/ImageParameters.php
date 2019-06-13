<?php
namespace Tinyga\ImageOptimizer\Image;

class ImageParameters
{
    /** @var string */
    protected $mime_type;

    /** @var int */
    protected $file_size;

    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /**
     * @param string $mime_type
     * @param int $file_size
     * @param int $width
     * @param int $height
     */
    function __construct($mime_type, $file_size, $width, $height)
    {
        $this->mime_type = (string)$mime_type;
        $this->file_size = (int)$file_size;
        $this->width = (int)$width;
        $this->height=  (int)$height;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->file_size;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }


    public function getResolution()
    {
        return [$this->width, $this->height];
    }


    public function isJPEG()
    {
        return $this->mime_type === ImageInterface::TYPE_JPEG;
    }

    public function isPNG()
    {
        return $this->mime_type === ImageInterface::TYPE_PNG;
    }

    public function isGIF()
    {
        return $this->mime_type === ImageInterface::TYPE_GIF;
    }
}