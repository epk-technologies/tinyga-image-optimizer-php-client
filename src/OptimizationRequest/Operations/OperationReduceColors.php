<?php

namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations;

class OperationReduceColors extends Operation
{
    const MIN_COLORS = 2;
    const MAX_COLORS = 1638400;

    const METHOD_DITHER = 'dither';
    const METHOD_GUANT = 'guant';
    const METHOD_TINYGA = 'tinyga';

    protected static $supported_methods = [
        self::METHOD_DITHER,
        self::METHOD_GUANT,
        self::METHOD_TINYGA
    ];

    /**
     * @var int
     */
    protected $colors;

    /**
     * @var string
     */
    protected $method = self::METHOD_TINYGA;

    /**
     * @param null|int $colors
     * @param null|string $method
     */
    public function __construct($colors = null, $method = null)
    {
        if($colors !== null){
            $this->setColors($colors);
        }

        if($method !== null){
            $this->setMethod($method);
        }
    }

    function getOperationType()
    {
        return Operations::OPERATION_REDUCE_COLORS;
    }


    /**
     * @return int
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * @param int $colors
     */
    public function setColors($colors)
    {
        $colors = (int)$colors;
        if($colors < self::MIN_COLORS || $colors > self::MAX_COLORS){
            throw new \InvalidArgumentException("Invalid colors count");
        }
        $this->colors = $colors;
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
            throw new \InvalidArgumentException("Invalid color reduction method");
        }
        $this->method = $method;
    }

    /**
     * @param string $value
     */
    protected function _initFromString($value)
    {
        if(!is_numeric($value)){
            throw new \InvalidArgumentException("Colors count expected");
        }
        $this->setColors((int)$value);
    }
}
