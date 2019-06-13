<?php
namespace Tinyga\ImageOptimizer\Image;

class ImageContent extends Image
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @param null|string $content
     * @param null|string $file_name
     */
    function __construct($content = null, $file_name = null)
    {
        parent::__construct($file_name);
        if($content !== null){
            $this->setContent($content);
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {
        if(!$this->content){
            throw new \RuntimeException("Image content is not defined");
        }
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}