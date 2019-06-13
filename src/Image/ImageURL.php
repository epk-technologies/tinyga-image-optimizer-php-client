<?php
namespace Tinyga\ImageOptimizer\Image;


class ImageURL extends Image
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @param string $url
     * @param null|string $file_name
     */
    function __construct($url, $file_name = null)
    {
        $this->setUrl($url);

        if(trim($file_name) === ''){
            $file_name = basename(explode('?', $this->url)[0]);
        }
        parent::__construct($file_name);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    protected function setUrl($url)
    {
        if(!filter_var((string)$url, FILTER_VALIDATE_URL)){
            throw new \InvalidArgumentException("Invalid URL");
        }

        $this->url = (string)$url;
    }

    /**
     * @return string
     */
    function getContent()
    {
        $content = @file_get_contents($this->url);
        if($content === false){
            throw new \RuntimeException("Failed to read URL '{$this->url}' content");
        }

        return $content;
    }
}