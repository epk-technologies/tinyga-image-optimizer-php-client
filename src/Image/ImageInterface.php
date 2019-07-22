<?php
namespace Tinyga\ImageOptimizer\Image;

interface ImageInterface
{
    const TYPE_JPEG = 'image/jpeg';
    const TYPE_PNG = 'image/png';
    const TYPE_GIF = 'image/gif';

    /**
     * @return string
     */
    function getImageContent();

    /**
     * @return ImageParameters
     */
    function getImageParameters();
}
