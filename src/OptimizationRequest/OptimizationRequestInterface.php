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

    const TEST_MODE_SUCCESS = 'SUCCESS';
    const TEST_MODE_INVALID_API_KEY = 'INVALID_API_KEY';
    const TEST_MODE_INSUFFICIENT_CREDIT = 'INSUFFICIENT_CREDIT';
    const TEST_MODE_INVALID_IMAGE = 'INVALID_IMAGE';
    const TEST_MODE_INVALID_PROCESSING_PARAMETERS = 'INVALID_PROCESSING_PARAMETERS';
    const TEST_MODE_INVALID_POST_URL = 'INVALID_POST_URL';
    const TEST_MODE_INTERNAL_SERVER_ERROR = 'INTERNAL_SERVER_ERROR';
    const TEST_MODE_PROCESSING_ERROR = 'PROCESSING_ERROR';
    const TEST_MODE_REJECTED_BY_PROCESSOR = 'REJECTED_BY_PROCESSOR';
    const TEST_MODE_DELIVERY_ERROR = 'DELIVERY_ERROR';

    const IMAGE_JPEG = 'image/jpeg';
    const IMAGE_PNG = 'image/png';
    const IMAGE_GIF = 'image/gif';

    const PARAM_QUALITY = 'quality';
    const PARAM_KEEP_METADATA = 'keep_metadata';
    const PARAM_POST_RESULT_TO_URL = 'post_result_to_url';
    const PARAM_TEST_MODE = 'test_mode';

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
     * @return string|null
     */
    public function getPostResultToUrl();

    /**
     * @return bool
     */
    public function isAsyncResult();

    /**
     * @return bool
     */
    public function isTestMode();

    /**
     * @return string|null
     */
    public function getTestMode();
}

