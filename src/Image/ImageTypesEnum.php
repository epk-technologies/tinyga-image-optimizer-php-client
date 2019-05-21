<?php

namespace Tinyga\ImageOptimizerClient\Image;

use InvalidArgumentException;

class ImageTypesEnum
{
    const TYPE_JPEG = 'image/jpeg';
    const TYPE_PNG = 'image/png';
    const TYPE_GIF = 'image/gif';
    const TYPE_WEBP = 'image/webp';

    /**
     * @return array
     */
    public static function getFileExtensionsForTypes()
    {
        return [
            self::TYPE_JPEG => 'jpg',
            self::TYPE_PNG => 'png',
            self::TYPE_GIF => 'gif',
            self::TYPE_WEBP => 'webp',
        ];
    }

    /**
     * @param $type
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public static function getFileExtensionByType($type)
    {
        self::checkValue($type);

        return self::getFileExtensionsForTypes()[$type];
    }

    /**
     * @return array
     */
    public static function getSupportedInputTypes()
    {
        return [
            self::TYPE_JPEG,
            self::TYPE_PNG,
            self::TYPE_GIF,
            self::TYPE_WEBP,
        ];
    }

    /**
     * @param $type
     *
     * @return bool
     */
    public static function isSupportedInputType($type)
    {
        return in_array($type, self::getSupportedInputTypes());
    }

    /**
     * @return array
     */
    public static function getSupportedOutputTypes()
    {
        return [
            self::TYPE_JPEG,
            self::TYPE_PNG,
            self::TYPE_GIF,
        ];
    }

    /**
     * @param $type
     *
     * @return bool
     */
    public static function isSupportedOutputType($type)
    {
        return in_array($type, self::getSupportedOutputTypes());
    }

    /**
     * @return array
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_JPEG,
            self::TYPE_PNG,
            self::TYPE_GIF,
            self::TYPE_WEBP,
        ];
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isAvailableType($type)
    {
        return in_array($type, self::getAvailableTypes());
    }

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public static function checkValue($value)
    {
        if (!self::isAvailableType($value)) {
            throw new InvalidArgumentException(sprintf('%s is not a valid type', $value));
        }
    }
}
