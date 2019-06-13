<?php
namespace Tinyga\ImageOptimizer\Image;


class ImageParametersResolver
{
    /**
     * @param ImageInterface $image
     * @return ImageParameters
     */
    public static function resolveImageParameters(ImageInterface $image)
    {
        $image_content = $image->getContent();
        $image_size = @getimagesizefromstring($image_content);
        if(!$image_size){
            throw new \InvalidArgumentException("Content is not valid image");
        }

        $image_parameters = new ImageParameters(
            $image_size['mime'],
            strlen($image_size),
            $image_size[0], $image_size[1]
        );

        return $image_parameters;
    }
}