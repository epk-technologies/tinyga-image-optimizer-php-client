<?php

namespace Tinyga\ImageOptimizerClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Tinyga\ImageOptimizerClient\Image\ImageWithFileName;
use Tinyga\ImageOptimizerClient\OptimizationRequest\OptimizationRequestInterface;
use Tinyga\ImageOptimizerClient\OptimizationRequest\OptimizationRequestValidator;

class ImageOptimizerClient
{
    const IMAGE_POST_FIELD_NAME = 'image';

    const API_KEY_PARAM_NAME = 'api-key';

    const DEFAULT_ENDPOINT = 'https://image-optimizer.tinyga.com/api/v1/';

    const API_METHOD_OPTIMIZE_IMAGE = 'optimize';

    const API_RESPONSE_HEADER_TASK_ID = 'Task-ID';


    /**
     * @var string
     */
    protected $api_endpoint_url = self::DEFAULT_ENDPOINT;

    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var HttpClient
     */
    protected $http_client;

    /**
     * @param string $api_key
     *
     * @param string $api_endpoint
     *
     * @throws ImageOptimizerClientException
     */
    public function __construct($api_key = null, $api_endpoint = null)
    {
        $api_key !== null && $this->setApiKey($api_key);
        $api_endpoint !== null && $this->setApiEndpointUrl($api_endpoint);
    }

    /**
     * @param string $api_method
     * @param array $with_parameters
     *
     * @return string
     */
    public function getApiEndpointUrl($api_method = '', array $with_parameters = [])
    {
        $url = $this->api_endpoint_url;
        if ($api_method) {
            $url .= $api_method;
        }
        if (!$with_parameters) {
            return $url;
        }

        return $url . '?' . http_build_query($with_parameters);
    }

    /**
     * @param string $api_endpoint_url
     *
     * @throws ImageOptimizerClientException
     */
    public function setApiEndpointUrl($api_endpoint_url)
    {
        if (!filter_var($api_endpoint_url, FILTER_VALIDATE_URL)) {
            throw new ImageOptimizerClientException(
                "Invalid API endpoint URL",
                ImageOptimizerClientException::CODE_INVALID_ENDPOINT
            );
        }
        $this->api_endpoint_url = rtrim($api_endpoint_url, '/') . '/';
    }

    /**
     * @return string|null
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @param string $api_key
     *
     * @throws ImageOptimizerClientException
     */
    public function setApiKey($api_key)
    {
        if (!preg_match('~^[\w\-]+$~', $api_key)) {
            throw new ImageOptimizerClientException(
                "Invalid API key format",
                ImageOptimizerClientException::CODE_INVALID_API_KEY
            );
        }
        $this->api_key = (string)$api_key;
    }

    /**
     * @param HttpClient $http_client
     */
    public function setHttpClient(HttpClient $http_client)
    {
        $this->http_client = $http_client;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (!$this->http_client) {
            $this->setHttpClient(new HttpClient());
        }

        return $this->http_client;
    }

    /**
     * @param OptimizationRequestInterface $optimization_request
     *
     * @return ImageOptimizerResult
     * @throws ImageOptimizerClientException
     */
    public function optimizeImage(OptimizationRequestInterface $optimization_request)
    {
        $this->validateRequest($optimization_request);

        $url = $this->getApiEndpointUrl(self::API_METHOD_OPTIMIZE_IMAGE);
        $client = $this->getHttpClient();
        try {
            $response = $client->request('POST', $url, [
                RequestOptions::MULTIPART => [
                    [
                        'name' => self::API_KEY_PARAM_NAME,
                        'contents' => $this->getApiKey()
                    ],
                    [
                        'name' => self::IMAGE_POST_FIELD_NAME,
                        'filename' => $optimization_request->getImageFileName(),
                        'contents' => $optimization_request->getImageContent(),
                    ],
                    [
                        'name' => OptimizationRequestInterface::PARAM_QUALITY,
                        'contents' => $optimization_request->getQuality()
                    ],
                    [
                        'name' => OptimizationRequestInterface::PARAM_KEEP_METADATA,
                        'contents' => implode(',', $optimization_request->getKeepMetadata())
                    ],
                    [
                        'name' => OptimizationRequestInterface::PARAM_POST_RESULT_TO_URL,
                        'contents' => $optimization_request->getPostResultToUrl()
                    ],
                    [
                        'name' => OptimizationRequestInterface::PARAM_TEST_MODE,
                        'contents' => $optimization_request->getTestMode()
                    ],
                ],
            ]);
        } catch (GuzzleException $e) {
            throw new ImageOptimizerClientException(
                "Optimization failed - {$e->getMessage()}",
                ImageOptimizerClientException::CODE_API_CALL_FAILED,
                $e
            );

        }

        $is_async_result = $optimization_request->isAsyncResult();
        return $is_async_result
            ? $this->getImageOptimizerAsyncResult($response)
            : $this->getImageOptimizerSyncResult($optimization_request, $response);
    }

    /**
     * @param OptimizationRequestInterface $optimization_request
     *
     * @throws ImageOptimizerClientException
     */
    protected function validateRequest(OptimizationRequestInterface $optimization_request)
    {
        $this->validateApiKey();
        $validator = new OptimizationRequestValidator();
        $validator->checkOptimizationRequest($optimization_request);
    }

    /**
     * @throws ImageOptimizerClientException
     */
    protected function validateApiKey()
    {
        $api_key = (string)$this->getApiKey();
        if ($api_key === '') {
            throw new ImageOptimizerClientException(
                "Missing API key",
                ImageOptimizerClientException::CODE_INVALID_API_KEY
            );
        }
    }

    /**
     * @param MessageInterface $response
     *
     * @return ImageOptimizerResult
     * @throws ImageOptimizerClientException
     */
    protected function getImageOptimizerAsyncResult(MessageInterface $response)
    {
//        $response = @json_decode($response->getBody());

        $task_id = $response->getHeaderLine(self::API_RESPONSE_HEADER_TASK_ID);

        if (!$task_id) {
            throw new ImageOptimizerClientException(
                "Optimization failed - task id not present",
                ImageOptimizerClientException::CODE_API_CALL_FAILED
            );
        }

        return new ImageOptimizerResult($task_id);
    }

    /**
     * @param OptimizationRequestInterface $optimization_request
     * @param MessageInterface $response
     *
     * @return ImageOptimizerResult
     * @throws ImageOptimizerClientException
     */
    protected function getImageOptimizerSyncResult(
        OptimizationRequestInterface $optimization_request,
        MessageInterface $response
    ) {
        $task_id = $response->getHeaderLine(self::API_RESPONSE_HEADER_TASK_ID);
        $image = $this->getImageWithFileName($optimization_request->getImageFileName(), $response->getBody());

        return new ImageOptimizerResult($task_id, $image);
    }

    /**
     * @param string $source_path
     * @param string $content
     *
     * @return ImageWithFileName
     * @throws ImageOptimizerClientException
     */
    protected function getImageWithFileName($source_path, $content)
    {
        try {
            return new ImageWithFileName($source_path, (string)$content);
        } catch (InvalidArgumentException $e) {
            throw new ImageOptimizerClientException(
                "Optimization failed - invalid image in API response: {$e->getMessage()}",
                ImageOptimizerClientException::CODE_API_CALL_FAILED
            );
        }
    }
}
