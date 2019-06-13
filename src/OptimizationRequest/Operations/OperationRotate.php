<?php
namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations;

class OperationRotate extends Operation
{
    const DEGREES_AUTO = 'auto';
    const DEGREES_90 = 90;
    const DEGREES_180 = 180;
    const DEGREES_270 = 270;

    protected static $supported_degrees = [
        self::DEGREES_AUTO,
        self::DEGREES_90,
        self::DEGREES_180,
        self::DEGREES_270,
    ];

    /**
     * @see RotationDegreesEnum
     * @var string|int
     */
    protected $degrees = self::DEGREES_AUTO;

    /**
     * @param string|int|null $degrees
     */
    public function __construct($degrees = null)
    {
        if($degrees !== null){
            $this->setDegrees($degrees);
        }
    }

    function getOperationName()
    {
        return Operations::OPERATION_ROTATE;
    }

    /**
     * @return int|string|null
     */
    public function getDegrees()
    {
        return $this->degrees;
    }

    /**
     * @param int|string $degrees
     */
    public function setDegrees($degrees)
    {
        if(!in_array($degrees, self::$supported_degrees)){
            throw new \InvalidArgumentException("Invalid degrees values for rotation");
        }
        $this->degrees = $degrees;
    }
}