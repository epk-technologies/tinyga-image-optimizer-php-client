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
        $path = parse_url($this->url, PHP_URL_PATH);
        if(
            $file_name === null &&
            preg_match('~\.\w+$~', $path)
        ){
            $file_name = basename($path);
        }
        parent::__construct((string)$file_name);
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
    function getImageContent()
    {
        $content = @file_get_contents($this->url);
        if($content === false){
            throw new \RuntimeException("Failed to read URL '{$this->url}' content");
        }
        return $content;
    }
}
