<?php
namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations;

class OperationResize extends Operation
{
    const METHOD_NEAREST = 'nearest';
    const METHOD_CUBIC = 'cubic';
    const METHOD_LANCZOS3 = 'lanczos3';
    const METHOD_LANCZOS2= 'lanczos2';

    const STRATEGY_EXACT = 'exact';
    const STRATEGY_FIT = 'fit';
    
    const SIZE_AUTO = 'auto';
    const MAX_SIZE = 16384;
    const MAX_PERCENT = 400;

    protected static $supported_methods = [
        self::METHOD_NEAREST,
        self::METHOD_CUBIC,
        self::METHOD_LANCZOS3,
        self::METHOD_LANCZOS2,
    ];

    protected static $supported_strategies = [
        self::STRATEGY_FIT,
        self::STRATEGY_EXACT
    ];

    /**
     * @var string|int
     */
    protected $width = self::SIZE_AUTO;

    /**
     * @var string|int
     */
    protected $height = self::SIZE_AUTO;

    /**
     * @var bool
     */
    protected $upscale_allowed = false;

    /**
     * @var string
     */
    protected $strategy = self::STRATEGY_FIT;

    /**
     * @var string
     */
    protected $method = self::METHOD_LANCZOS3;

    /**
     * @param int|string|null $width
     * @param int|string|null $height
     * @param string|null $strategy
     * @param bool|null $allow_upscale
     * @param string|null $method
     */
    public function __construct(
        $width = null,
        $height = null,
        $strategy = null,
        $allow_upscale = null,
        $method = null
    )
    {
        $width !== null && $this->setWidth($width);
        $height !== null && $this->setHeight($height);
        $strategy !== null && $this->setStrategy($strategy);
        $allow_upscale !== null && $this->setUpscaleAllowed($allow_upscale);
        $method !== null && $this->setMethod($method);
    }


    function getOperationName()
    {
        return Operations::OPERATION_RESIZE;
    }

    /**
     * @return int|string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int|string $width
     */
    public function setWidth($width)
    {
        $this->width = $this->resolveWidthHeightValue($width, "Invalid width");
    }

    /**
     * @return int|string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int|string $height
     */
    public function setHeight($height)
    {
        $this->height = $this->resolveWidthHeightValue($height, "Invalid height");
    }


    /**
     * @param string|int $value
     * @param string $error_message
     * @return int|string
     */
    protected function resolveWidthHeightValue($value, $error_message)
    {
        if($value === self::SIZE_AUTO){
            return $value;
        }

        if(preg_match('~^(\d+)(?:px)?$~', (string)$value)){
            $value = (int)str_replace('px', '', (string)$value);
            if($value <= 0){
                throw new \InvalidArgumentException($error_message);
            }

            if($value > self::MAX_SIZE){
                throw new \InvalidArgumentException($error_message);
            }

            return $value;
        }


        if(!preg_match('^(\d+(?:\.\d+)?)%$', (string)$value, $m)){
            throw new \InvalidArgumentException($error_message);
        }

        $percent = (float)$m[1];
        if($percent <= 0){
            throw new \InvalidArgumentException($error_message);
        }

        if($percent > self::MAX_PERCENT){
            throw new \InvalidArgumentException($error_message);
        }

        return $value;

    }

    /**
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @param string $strategy
     */
    public function setStrategy($strategy)
    {
        if(!in_array($strategy, self::$supported_strategies)){
            throw new \InvalidArgumentException("Invalid resize strategy '{$strategy}'");
        }
        $this->strategy = $strategy;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        if(!in_array($method, self::$supported_methods)){
            throw new \InvalidArgumentException("Invalid resize method '{$method}'");
        }
        $this->method = $method;
    }

    public function isUpscaleAllowed()
    {
        return $this->upscale_allowed;
    }


    public function setUpscaleAllowed($upscale_allowed)
    {
        $this->upscale_allowed = (bool)$upscale_allowed;
    }

    /**
     * @param string $value
     */
    protected function _initFromString($value)
    {
        $value_pattern = 'auto|\d+(?:px)?|\d+(\.\d+)?%';
        if(!preg_match('~^(?:'.$value_pattern.')(?:x(?:'.$value_pattern.'))?$~', $value)){
            throw new \InvalidArgumentException("Size or Width x Height format expected");
        }

        $wh = explode('x', $value);
        if(!isset($wh[1])){
            $wh[1] = $wh[0];
        }

        $this->setWidth($wh[0]);
        $this->setHeight($wh[1]);
    }
}
