<?php
namespace Tinyga\ImageOptimizer;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Tinyga\ImageOptimizer\Image\Image;
use Tinyga\ImageOptimizer\Image\ImageContent;
use Tinyga\ImageOptimizer\Image\ImageURL;

class ImageOptimizerClient
{
    const API_KEY_PARAM = 'api-key';
    const DEFAULT_ENDPOINT = 'https://image-optimizer.tinyga.com/api/v1/';
    const DEFAULT_CLIENT_TIMEOUT = 90.0;

    const PARAM_IMAGE = 'image';
    const PARAM_IMAGE_URL = 'image_url';

    const PARAM_OUTPUT = 'output';
    const PARAM_POST_RESULT_TO_URL = 'post_result_to_url';
    const PARAM_OPERATIONS = 'operations';
    const PARAM_TEST = 'test';

    const OPTIMIZATION_API_METHOD = 'process-image';
    const TASK_ID_HEADER = 'Task-ID';

    const RESP_ERROR_CODE = 'error_code';
    const RESP_ERROR_MESSAGE = 'error_message';


    /**
     * @var string
     */
    protected $api_endpoint_url = self::DEFAULT_ENDPOINT;

    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var float
     */
    protected $client_timeout = self::DEFAULT_CLIENT_TIMEOUT;


    /**
     * @param string|null $api_key
     * @param string|null $api_endpoint
     */
    public function __construct($api_key = null, $api_endpoint = null)
    {
        if($api_key !== null){
            $this->setApiKey($api_key);
        }

        if($api_endpoint !== null){
            $this->setApiEndpointUrl($api_endpoint);
        }
    }

    /**
     * @return float
     */
    public function getClientTimeout()
    {
        return $this->client_timeout;
    }

    /**
     * @param float $client_timeout
     */
    public function setClientTimeout($client_timeout)
    {
        $this->client_timeout = (float)$client_timeout;
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
     */
    public function setApiEndpointUrl($api_endpoint_url)
    {
        if (!filter_var($api_endpoint_url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Invalid API endpoint URL format");
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
     */
    public function setApiKey($api_key)
    {
        if (!preg_match('~^[\w\-]+$~', $api_key)) {
            throw new InvalidArgumentException("Invalid API key format");
        }
        $this->api_key = (string)$api_key;
    }


    /**
     * Send image for optimization/processing
     * If anything fails, @see OptimizationException is thrown
     * If post_result_to_url is defined in request:
     * - result with task ID and without optimized image will be returned to be processed later
     * - @see ImageOptimizerPostResultHandler how to handle request from Tinyga when optimization is ready
     *
     *
     * @param OptimizationRequest $request
     * @return OptimizationResult
     * @throws OptimizationException
     */
    public function optimizeImage(OptimizationRequest $request)
    {
        if(!$this->api_key){
            throw new \RuntimeException("API key is not defined");
        }

        $request->validate();
        $post_params = $this->preparePostParameters($request, $submit_method);


        $client = new HttpClient([
            'base_uri' => $this->getApiEndpointUrl(),
            'timeout' => $this->client_timeout
        ]);

        try {

            $response = $client->post(self::OPTIMIZATION_API_METHOD, [$submit_method => $post_params]);
            $result = $this->processResponse($request, $response);
            return $result;


        } catch(OptimizationException $e){

            throw $e;

        } catch (\Exception $e){

            $error_code = OptimizationException::ERR_CLIENT_ERROR;
            $error_message = $e->getMessage();
            $task_id = null;

            $response = null;
            if($e instanceof RequestException){
                $response = $e->getResponse();
            }

            if(
                $response &&
                $response->getHeaderLine('Content-Type') === 'application/json'
            ){
                $body = (string)$response->getBody();
                $decoded = @json_decode($body, true);
                if(isset($decoded[self::RESP_ERROR_CODE])){
                    $error_code = $decoded[self::RESP_ERROR_CODE];
                }

                if(isset($decoded[self::RESP_ERROR_MESSAGE])){
                    $error_message = $decoded[self::RESP_ERROR_MESSAGE];
                }
            }

            if(
                $response &&
                $response->getHeaderLine(self::TASK_ID_HEADER)
            ){
                $task_id = $response->getHeaderLine(self::TASK_ID_HEADER);
            }

            throw new OptimizationException($error_code, $error_message,  $task_id,0, $e);

        }
    }

    /**
     * @param OptimizationRequest $request
     * @param null|string $submit_method
     * @return array
     */
    protected function preparePostParameters(OptimizationRequest $request, &$submit_method = null)
    {
        $inline_params = [
            self::API_KEY_PARAM => $this->getApiKey(),
            self::PARAM_OUTPUT => json_encode($request->getOutputParameters()),
        ];

        if($request->isTest()){
            $inline_params[self::PARAM_TEST] = 1;
        }

        if($request->getPostResultToUrl()){
            $inline_params[self::PARAM_POST_RESULT_TO_URL] = $request->getPostResultToUrl();
        }

        $operations = $request->getOperations();
        if($operations){
            $inline_params[self::PARAM_OPERATIONS] = json_encode(array_values($operations));
        }

        $image = $request->getImage();
        if($image instanceof ImageURL){

            $inline_params[self::PARAM_IMAGE_URL] = $image->getUrl();
            $submit_method = RequestOptions::FORM_PARAMS;
            $post_params = $inline_params;

        } else {

            $submit_method = RequestOptions::MULTIPART;
            $post_params = [];
            foreach($inline_params as $param => $value){
                $post_params[] = ['name' => $param, 'contents' => $value];
            }

            if($image instanceof Image){

                $post_params[] = [
                    'name' => self::PARAM_IMAGE,
                    'contents' => $image->getImageContent(),
                    'filename' => $image->getFileName()
                ];

            } else {

                $post_params[] = [
                    'name' => self::PARAM_IMAGE,
                    'contents' => $image->getImageContent()
                ];

            }
        }

        return $post_params;
    }

    /**
     * @param OptimizationRequest $request
     * @param ResponseInterface $response
     * @return OptimizationResult
     * @throws OptimizationException
     */
    protected function processResponse(OptimizationRequest $request, ResponseInterface $response)
    {
        $task_id = $response->getHeaderLine(self::TASK_ID_HEADER);
        if(!$task_id){
            throw new OptimizationException(
                OptimizationException::ERR_PROTOCOL_ERROR,
                "Missing Task-ID header in response"
            );
        }

        if($request->getPostResultToUrl()){
            return new OptimizationResult($task_id);
        }

        $content_type = $response->getHeaderLine('Content-Type');
        if(!preg_match('~^image/\w+$~', $content_type)){
            throw new OptimizationException(
                OptimizationException::ERR_PROTOCOL_ERROR,
                "Image expected as an optimization result, {$content_type} given",
                $task_id
            );
        }

        $disposition = $response->getHeaderLine('Content-Disposition');
        $result_fn = '';
        if(preg_match('~filename="?([^"]+)"?$~', $disposition, $m)){
            $result_fn = $m[1];
        }

        $content = (string)$response->getBody();
        if(md5($content) !== $response->getHeaderLine('Content-MD5')){
            throw new OptimizationException(
                OptimizationException::ERR_PROTOCOL_ERROR,
                "Image content malformed - MD5 checksums do not match",
                $task_id
            );
        }

        $image = new ImageContent($content, $result_fn);
        return new OptimizationResult($task_id, $image);
    }
}
