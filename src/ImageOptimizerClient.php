<?php
namespace Tinyga\ImageOptimizerClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Tinyga\ImageOptimizerClient\OptimizationRequest\OptimizationRequestInterface;
use Tinyga\ImageOptimizerClient\OptimizationRequest\OptimizationRequestValidator;

class ImageOptimizerClient
{
    const IMAGE_POST_FIELD_NAME = 'image';

    const API_KEY_PARAM_NAME = 'api-key';

    const DEFAULT_ENDPOINT = 'https://image-optimizer.tinyga.com/api/v1/';

    const API_METHOD_OPTIMIZE_IMAGE = 'optimize-image';


    /**
     * @var string
     */
    protected $api_endpoint_url = self::DEFAULT_ENDPOINT;

    /**
     * @var string
     */
    protected $api_key;

    /**
     * @param string $api_endpoint
     * @param string $api_key
     * @throws ImageOptimizerClientException
     */
    public function __construct($api_key = null, $api_endpoint = null)
    {
        $api_key !== null && $this->setApiKey($api_key);
        $api_endpoint !== null && $this->setApiEndpointUrl($api_endpoint);
    }

    /**
     * @param array $with_parameters
     * @return string
     */
    public function getApiEndpointUrl(array $with_parameters = [])
    {
        if(!$with_parameters){
            return $this->api_endpoint_url;
        }
        return $this->api_endpoint_url . '?' . http_build_query($with_parameters);
    }

    /**
     * @param string $api_endpoint_url
     * @throws ImageOptimizerClientException
     */
    public function setApiEndpointUrl($api_endpoint_url)
    {
        if(!filter_var($api_endpoint_url, FILTER_VALIDATE_URL)){
            throw new ImageOptimizerClientException(
                "Invalid API endpoint URL",
                ImageOptimizerClientException::CODE_INVALID_ENDPOINT
            );
        }
        $this->api_endpoint_url = rtrim($api_endpoint_url, '/') . '/';
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @param string $api_key
     * @throws ImageOptimizerClientException
     */
    public function setApiKey($api_key)
    {
        if(!preg_match('~^[\w\-]+$~', $api_key)){
            throw new ImageOptimizerClientException(
                "Invalid API key format",
                ImageOptimizerClientException::CODE_INVALID_API_KEY
            );
        }
        $this->api_key = (string)$api_key;
    }

    /**
     * @param OptimizationRequestInterface $optimization_request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws ImageOptimizerClientException
     */
    public function optimizeImage(OptimizationRequestInterface $optimization_request)
    {
        $api_key = (string)$this->getApiKey();
        if($api_key === ''){
            throw new ImageOptimizerClientException(
                "Missing API key",
                ImageOptimizerClientException::CODE_INVALID_API_KEY
            );
        }

        $validator = new OptimizationRequestValidator();
        $validator->checkOptimizationRequest($optimization_request);

        $url_params = [
            self::API_KEY_PARAM_NAME => $api_key,
            OptimizationRequestInterface::PARAM_QUALITY => $optimization_request->getQuality(),
            OptimizationRequestInterface::PARAM_KEEP_METADATA => implode(',', $optimization_request->getKeepMetadata())
        ];

        $url = $this->getApiEndpointUrl($url_params);

        $client = new HttpClient();

        try {

            return $client->request('POST', $url, [
                RequestOptions::MULTIPART => [
                    [
                        'name' => self::IMAGE_POST_FIELD_NAME,
                        'filename' => $optimization_request->getImageFileName(),
                        'contents' => $optimization_request->getImageContent()
                    ]
                ]
            ]);

        } catch(GuzzleException $e){

            throw new ImageOptimizerClientException(
                "Optimization failed - {$e->getMessage()}",
                ImageOptimizerClientException::CODE_API_CALL_FAILED,
                $e
            );

        }


    }

}