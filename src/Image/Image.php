<?php

namespace Tinyga\ImageOptimizerClient\Image;

use InvalidArgumentException;

class Image
{
    /**
     * @var ImageParameters
     */
    protected $image_parameters;

    /**
     * @var string
     */
    protected $content;

    /**
     * Image constructor.
     *
     * @param null $content
     *
     * @throws InvalidArgumentException
     */
    function __construct($content = null)
    {
        $content !== null && $this->setContent($content);
    }

    /**
     * @return ImageParameters
     */
    public function getImageParameters()
    {
        return $this->image_parameters;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     *
     * @throws InvalidArgumentException
     */
    public function setContent($content)
    {
        $resolver = new ImageParametersResolver();
        $this->image_parameters = $resolver->resolveParametersFromContent($content);
        $this->content = $content;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->getContent();
    }

}