<?php

namespace Tinyga\ImageOptimizerClient\Image;

use Exception;

class ImageWithFileName extends Image
{
    /** @var string */
    protected $file_name;

    /**
     * ImageWithFileName constructor.
     *
     * @param null $source_path
     * @param null $content
     */
    function __construct($source_path = null, $content = null)
    {
        parent::__construct($content);
        $source_path !== null && $this->setFileName($source_path);
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
        $this->file_name = $file_name;
    }
}