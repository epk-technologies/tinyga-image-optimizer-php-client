<?php

namespace Tinyga\ImageOptimizerClient\Image;

use Exception;
use InvalidArgumentException;

class ImageParameters extends JsonSerializableObject
{
    /** @var string */
    protected $mime_type;

    /** @var int */
    protected $file_size;

    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /** @var string */
    protected $md5_checksum;

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @param $mime_type
     *
     * @throws InvalidArgumentException
     */
    public function setMimeType($mime_type)
    {
        ImageTypesEnum::checkValue($mime_type);
        $this->mime_type = $mime_type;
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->file_size;
    }

    /**
     * @param $file_size
     */
    public function setFileSize($file_size)
    {
        if ($file_size <= 0) {
            throw new InvalidArgumentException("Invalid size");
        }
        $this->file_size = $file_size;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param $width
     */
    public function setWidth($width)
    {
        if ($width <= 0) {
            throw new InvalidArgumentException("Invalid width");
        }
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param $height
     */
    public function setHeight($height)
    {
        if ($height <= 0) {
            throw new InvalidArgumentException("Invalid height");
        }
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getKilopixels()
    {
        return (float)$this->width * (float)$this->height / 1000.0;
    }

    /**
     * @return string
     */
    public function getMd5Checksum()
    {
        return $this->md5_checksum;
    }

    /**
     * @param $md5_checksum
     */
    public function setMD5Checksum($md5_checksum)
    {
        if (!preg_match('~^[0-9a-f]{32}$~', $md5_checksum)) {
            throw new InvalidArgumentException("Invalid MD5 checksum format");
        }
        $this->md5_checksum = $md5_checksum;
    }

    /**
     * @return array
     */
    public function getResolution()
    {
        return [$this->width, $this->height];
    }

    /**
     * @param $width
     * @param $height
     */
    public function setResolution($width, $height)
    {
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getFileExtensionByType()
    {
        return ImageTypesEnum::getFileExtensionByType($this->mime_type);
    }

    /**
     * @return bool
     */
    public function isJPEG()
    {
        return $this->mime_type === ImageTypesEnum::TYPE_JPEG;
    }

    /**
     * @return bool
     */
    public function isPNG()
    {
        return $this->mime_type === ImageTypesEnum::TYPE_PNG;
    }

    /**
     * @return bool
     */
    public function isGIF()
    {
        return $this->mime_type === ImageTypesEnum::TYPE_GIF;
    }

    /**
     * @return bool
     */
    public function isWebP()
    {
        return $this->mime_type === ImageTypesEnum::TYPE_WEBP;
    }

}