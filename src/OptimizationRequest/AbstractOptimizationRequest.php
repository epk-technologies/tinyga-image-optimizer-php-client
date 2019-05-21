<?php

namespace Tinyga\ImageOptimizerClient\OptimizationRequest;

use Tinyga\ImageOptimizerClient\ImageOptimizerClientException;

abstract class AbstractOptimizationRequest implements OptimizationRequestInterface
{
    /**
     * @var int
     */
    protected $quality = OptimizationRequestInterface::LOSSLESS_QUALITY;

    /**
     * @var array
     */
    protected $keep_metadata = [OptimizationRequestInterface::KEEP_META_ALL];

    /**
     * @var string
     */
    protected $post_result_to_url = '';

    /**
     * @var bool
     */
    protected $async_result = false;

    /**
     * @var string
     */
    protected $test_mode = '';

    /**
     * @param int|null $quality
     * @param array|null $keep_metadata
     * @param string|null $post_result_to_url
     * @param string|null $test_mode
     *
     * @throws ImageOptimizerClientException
     */
    public function __construct(
        $quality = null,
        array $keep_metadata = null,
        $post_result_to_url = null,
        $test_mode = null
    ) {
        $quality !== null && $this->setQuality($quality);
        $keep_metadata !== null && $this->setKeepMetadata($keep_metadata);
        $post_result_to_url !== null && $this->setPostResultToUrl($post_result_to_url);
        $test_mode !== null && $this->setTestMode($test_mode);
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param int $quality
     *
     * @throws ImageOptimizerClientException
     */
    public function setQuality($quality)
    {
        (new OptimizationRequestValidator())->checkQuality($quality);
        $this->quality = $quality;
    }

    /**
     * @return array
     */
    public function getKeepMetadata()
    {
        return $this->keep_metadata;
    }

    /**
     * @param array $keep_metadata
     *
     * @throws ImageOptimizerClientException
     */
    public function setKeepMetadata($keep_metadata)
    {
        (new OptimizationRequestValidator())->checkKeepMetadata($keep_metadata);
        $this->keep_metadata = $keep_metadata;
    }

    /**
     * @return string
     */
    public function getPostResultToUrl()
    {
        return $this->post_result_to_url;
    }

    /**
     * @param string $post_result_to_url
     */
    public function setPostResultToUrl($post_result_to_url)
    {
        $this->post_result_to_url = (string)$post_result_to_url;
        $this->async_result = true;
    }

    /**
     * @return bool
     */
    public function isAsyncResult()
    {
        return $this->async_result;
    }

    /**
     * @return bool
     */
    public function isTestMode()
    {
        return $this->test_mode !== '';
    }

    /**
     * @return string
     */
    public function getTestMode()
    {
        return $this->test_mode;
    }

    /**
     * @param string $test_mode
     */
    public function setTestMode($test_mode)
    {
        $this->test_mode = $test_mode;
    }
}