<?php
namespace Tinyga\ImageOptimizerClient\OptimizationRequest;

interface OptimizationRequestInterface
{
    const LOSSLESS_QUALITY = 100;
    
    const MIN_QUALITY = 1;
    const MAX_QUALITY = self::LOSSLESS_QUALITY;

    const KEEP_META_ALL = 'all';

    const KEEP_META_PROFILE = 'profile';
    const KEEP_META_DATE = 'date';
    const KEEP_META_COPYRIGHT = 'copyright';
    const KEEP_META_GEOTAG = 'geotag';
    const KEEP_META_ORIENTATION = 'orientation';

    const IMAGE_JPEG = 'image/jpeg';
    const IMAGE_PNG = 'image/png';
    const IMAGE_GIF = 'image/gif';

    const PARAM_QUALITY = 'quality';
    const PARAM_KEEP_METADATA = 'keep_metadata';
    const PARAM_TESTING = 'testing';

    const KEEP_METADATA_SEPARATOR = ',';


    /**
     * @return int
     */
    public function getQuality();

    /**
     * @return array
     */
    public function getKeepMetadata();

    /**
     * @return string
     */
    public function getImageFileName();

    /**
     * @return string
     */
    public function getImageContent();

    /**
     * @return bool
     */
    public function isTestingRequest();
}

