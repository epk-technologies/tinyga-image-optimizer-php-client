<?php
namespace Tinyga\ImageOptimizer\Image;

class ImageContent extends Image
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @param string $content
     * @param string $file_name
     */
    function __construct($content, $file_name = '')
    {
        parent::__construct($file_name);
        $this->content = $content;
        $this->getImageParameters();
    }

    /**
     * @return string
     */
    public function getImageContent()
    {
        return $this->content;
    }
}
