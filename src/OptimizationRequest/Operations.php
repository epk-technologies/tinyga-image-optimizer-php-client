<?php
namespace Tinyga\ImageOptimizer\OptimizationRequest;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations\Operation;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\OperationFlip;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\OperationGamma;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\OperationGrayscale;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\OperationReduceColors;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\OperationResize;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\OperationRotate;

class Operations
{
    const OPERATION_RESIZE = 'resize';
    const OPERATION_ROTATE = 'rotate';
    const OPERATION_FLIP = 'flip';
    const OPERATION_GAMMA = 'gamma';
    const OPERATION_GRAYSCALE = 'grayscale';
    const OPERATION_REDUCE_COLORS = 'reduce-colors';

    public static function getOperationClasses()
    {
        return [
            self::OPERATION_RESIZE => OperationResize::class,
            self::OPERATION_ROTATE => OperationRotate::class,
            self::OPERATION_FLIP => OperationFlip::class,
            self::OPERATION_GAMMA => OperationGamma::class,
            self::OPERATION_GRAYSCALE => OperationGrayscale::class,
            self::OPERATION_REDUCE_COLORS => OperationReduceColors::class,
        ];
    }

    /**
     * @param string $operation
     * @return string|Operation
     */
    public static function getOperationClass($operation)
    {
        $classes = self::getOperationClasses();
        if(!isset($classes[$operation])){
            throw new \InvalidArgumentException("Invalid operation '{$operation}'");
        }
        return $classes[$operation];
    }


    /**
     * @param string $operation_name
     * @param null|array|string $params operation configuration as array or string
     * @return Operation
     * @see Operation::initFromArray()
     * @see Operation::initFromString()
     */
    public static function createOperation($operation_name, $params = null)
    {
        $operation_class = self::getOperationClass($operation_name);
        /** @var Operation $operation */
        $operation = new $operation_class();

        if($params === null){
            return $operation;
        }

        if(is_array($params)){
            $operation->initFromArray($params);
        } else {
            $operation->initFromString($params);
        }

        return $operation;
    }

    /**
     * @param string|null $axis
     * @return OperationFlip
     */
    public static function createFlipOperation($axis = null)
    {
        $operation = new OperationFlip();
        $axis !== null && $operation->setAxis($axis);
        return $operation;
    }

    /**
     * @param float|null $gamma
     * @return OperationGamma
     */
    public static function createGammaOperation($gamma = null)
    {
        return new OperationGamma($gamma);
    }

    /**
     * @return OperationGrayscale
     */
    public static function createGrayscaleOperation()
    {
        return new OperationGrayscale();
    }

    /**
     * @param int|null $colors
     * @param string|null $method
     * @return OperationReduceColors
     */
    public static function createReduceColorsOperation($colors = null, $method = null)
    {
        return new OperationReduceColors($colors, $method);
    }

    /**
     * @param null|int|string $width
     * @param null|int|string $height
     * @param string|null $strategy
     * @param bool|null $upscale_allowed
     * @param string|null $method
     * @return OperationResize
     */
    public static function createResizeOperation(
        $width = null,
        $height = null,
        $strategy = null,
        $upscale_allowed = null,
        $method = null
    )
    {
        return new OperationResize(
            $width,
            $height,
            $strategy,
            $upscale_allowed,
            $method
        );
    }
    
    /**
     * @param string|int|null $degrees
     * @return OperationRotate
     */
    public function createRotateOperation($degrees = null)
    {
        return new OperationRotate($degrees);
    }
}