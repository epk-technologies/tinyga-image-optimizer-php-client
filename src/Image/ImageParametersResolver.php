<?php

namespace Tinyga\ImageOptimizerClient\Image;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use SplFileInfo;

class ImageParametersResolver
{
    /**
     * @param string $image_content
     *
     * @return ImageParameters
     * @throws InvalidArgumentException
     */
    public function resolveParametersFromContent($image_content)
    {
        $image_size = @getimagesizefromstring($image_content);
        if (!$image_size) {
            throw new InvalidArgumentException("Content is not valid image");
        }

        $image_parameters = new ImageParameters();
        $image_parameters->setResolution($image_size[0], $image_size[1]);
        $image_parameters->setFileSize(strlen($image_content));
        $image_parameters->setMimeType($image_size['mime']);
        $image_parameters->setMD5Checksum(md5($image_content));

        return $image_parameters;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return ImageParameters
     * @throws Exception
     */
    public function resolveImageParametersFromFile(SplFileInfo $file)
    {
        $file_path = $file->getPathname();
        if (!$file->isFile() || !$file->isReadable()) {
            throw new RuntimeException("File '{$file_path}' does not exist or is not readable");
        }

        $image_size = @getimagesize($file_path);
        if (!$image_size) {
            throw new InvalidArgumentException("File '{$file_path}' is not valid image");
        }

        $image_parameters = new ImageParameters();
        $image_parameters->setResolution($image_size[0], $image_size[1]);
        $image_parameters->setFileSize(filesize($file_path));
        $image_parameters->setMimeType($image_size['mime']);
        $image_parameters->setMD5Checksum(md5_file($file_path));

        return $image_parameters;
    }
}