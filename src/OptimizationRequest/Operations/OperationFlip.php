<?php

namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\Operation;

class OperationFlip extends Operation
{
    const AXIS_X = 'x';
    const AXIS_Y = 'y';

    protected static $supported_axis = [
        self::AXIS_X,
        self::AXIS_Y,

    ];

    /**
     * @var string
     */
    protected $axis;

    /**
     * @param null|string $axis
     */
    public function __construct($axis = null)
    {
        if($axis !== null){
            $this->setAxis($axis);
        }
    }


    function getOperationName()
    {
        return Operations::OPERATION_FLIP;
    }

    /**
     * @return string
     */
    public function getAxis()
    {
        return $this->axis;
    }

    /**
     * @param string $axis
     */
    public function setAxis($axis)
    {
        if(!in_array($axis, self::$supported_axis)){
            throw new \InvalidArgumentException("Invalid axis for flip operation");
        }
        $this->axis = $axis;
    }
}