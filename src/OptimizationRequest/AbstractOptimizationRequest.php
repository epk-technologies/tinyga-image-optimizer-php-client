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
     * @var bool
     */
    protected $testing_request = false;

    /**
     * @param int|null $quality
     * @param array|null $keep_metadata
     * @throws ImageOptimizerClientException
     */
    public function __construct($quality = null, array $keep_metadata = null)
    {
        $quality !== null && $this->setQuality($quality);
        $keep_metadata !== null && $this->setKeepMetadata($keep_metadata);
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
     * @throws ImageOptimizerClientException
     */
    public function setKeepMetadata($keep_metadata)
    {
        (new OptimizationRequestValidator())->checkKeepMetadata($keep_metadata);
        $this->keep_metadata = $keep_metadata;
    }

    /**
     * @return bool
     */
    public function isTestingRequest()
    {
        return $this->testing_request;
    }

    /**
     * @param bool $testing_request
     */
    public function setTestingRequest($testing_request)
    {
        $this->testing_request = $testing_request;
    }
}