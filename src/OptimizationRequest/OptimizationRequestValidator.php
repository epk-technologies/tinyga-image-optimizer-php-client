<?php
namespace Tinyga\ImageOptimizerClient\OptimizationRequest;

use Tinyga\ImageOptimizerClient\ImageOptimizerClientException;

class OptimizationRequestValidator
{

    /**
     * @param int $quality
     * @throws ImageOptimizerClientException
     */
    public function checkQuality($quality)
    {
        if(
            !is_int($quality) ||
            $quality < OptimizationRequestInterface::MIN_QUALITY ||
            $quality > OptimizationRequestInterface::MAX_QUALITY
        ){
            throw new ImageOptimizerClientException(
                sprintf(
                    "Invalid quality - must be number between %d and %d",
                    OptimizationRequestInterface::MIN_QUALITY,
                    OptimizationRequestInterface::MAX_QUALITY
                ),
                ImageOptimizerClientException::CODE_INVALID_QUALITY
            );
        }
    }

    /**
     * @param array $metadata
     * @throws ImageOptimizerClientException
     */
    public function checkKeepMetadata($metadata)
    {
        if(!is_array($metadata)){
            throw new ImageOptimizerClientException(
                sprintf("Keep metadata must be an array, %s given.", gettype($metadata)),
                ImageOptimizerClientException::CODE_INVALID_METADATA
            );
        }

        if(!$metadata){
            return;
        }

        $supported = [
            OptimizationRequestInterface::KEEP_META_ALL,

            OptimizationRequestInterface::KEEP_META_PROFILE,
            OptimizationRequestInterface::KEEP_META_DATE,
            OptimizationRequestInterface::KEEP_META_COPYRIGHT,
            OptimizationRequestInterface::KEEP_META_GEOTAG,
            OptimizationRequestInterface::KEEP_META_ORIENTATION
        ];

        foreach($metadata as $meta){
            if(!in_array($meta, $supported)){
                throw new ImageOptimizerClientException(
                    sprintf("Keep metadata value '%s' is not supported.", $meta),
                    ImageOptimizerClientException::CODE_INVALID_METADATA
                );
            }
        }
    }

    /**
     * @param string $image_file_name
     * @throws ImageOptimizerClientException
     */
    public function checkImageFileName($image_file_name)
    {
        if(trim($image_file_name) === ''){
            throw new ImageOptimizerClientException(
                "Missing image file name in request",
                ImageOptimizerClientException::CODE_INVALID_FILE_NAME
            );
        }
    }

    /**
     * @param string $image_content
     * @throws ImageOptimizerClientException
     */
    public function checkImageContent($image_content, &$mime_type = null)
    {
        if(!is_string($image_content) || $image_content === ''){
            throw new ImageOptimizerClientException(
                'Missing image content',
                ImageOptimizerClientException::CODE_INVALID_FILE_NAME
            );
        }

        $supported_types = [
            OptimizationRequestInterface::IMAGE_JPEG,
            OptimizationRequestInterface::IMAGE_GIF,
            OptimizationRequestInterface::IMAGE_PNG,
        ];

        if(function_exists('getimagesizefromstring')){
            $size = @getimagesizefromstring($image_content);
            if($size === false){
                throw new ImageOptimizerClientException(
                    'Invalid image content',
                    ImageOptimizerClientException::CODE_INVALID_FILE_NAME
                );
            }

            if(!in_array($size['mime'], $supported_types)){
                throw new ImageOptimizerClientException(
                    "Image format '{$size['mime']}' is not supported yet",
                    ImageOptimizerClientException::CODE_INVALID_FILE_NAME
                );
            }

            $mime_type = $size['mime'];
        }

        throw new \RuntimeException("GD extension not loaded");
        //todo:  imagick if not GD ...
    }

    /**
     * @param OptimizationRequestInterface $optimization_request
     * @throws ImageOptimizerClientException
     */
    public function checkOptimizationRequest(OptimizationRequestInterface $optimization_request)
    {
        $this->checkQuality($optimization_request->getQuality());
        $this->checkKeepMetadata($optimization_request->getKeepMetadata());
        $this->checkImageFileName($optimization_request->getImageFileName());
        $this->checkImageContent($optimization_request->getImageContent());
    }
}