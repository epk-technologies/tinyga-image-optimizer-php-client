<?php

namespace Tinyga\ImageOptimizerClient;

use Exception;

class ImageOptimizerClientException extends Exception
{
    const CODE_INVALID_API_KEY = 10;
    const CODE_INVALID_ENDPOINT = 20;
    const CODE_INVALID_QUALITY = 30;
    const CODE_INVALID_METADATA = 40;
    const CODE_INVALID_FILE_NAME = 50;
    const CODE_INVALID_IMAGE = 60;
    const CODE_INVALID_RESULT_URL = 70;
    const CODE_INVALID_TEST_MODE = 80;

    const CODE_API_CALL_FAILED = 90;
    const CODE_API_ERROR = 99;

    const CODE_INSUFFICIENT_CREDIT = 100;
    const CODE_OPTIMIZATION_FAILED = 200;

}