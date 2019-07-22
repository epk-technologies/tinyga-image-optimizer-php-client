<?php
namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations;

class OperationGamma extends Operation
{
    const MAX_GAMMA = 3.0;
    const MIN_GAMMA = 1.0;
    const DEFAULT_GAMMA = 2.2;

    /**
     * @var float
     */
    protected $gamma = self::DEFAULT_GAMMA;

    /**
     * @param float|null $gamma
     */
    public function __construct($gamma = null)
    {
        if($gamma !== null){
            $this->setGamma($gamma);
        }
    }

    /**
     * @return string
     */
    function getOperationType()
    {
        return Operations::OPERATION_GAMMA;
    }

    /**
     * @return float
     */
    public function getGamma()
    {
        return $this->gamma;
    }

    /**
     * @param float $gamma
     */
    public function setGamma($gamma)
    {
        $gamma = (float)$gamma;
        if($gamma < self::MIN_GAMMA || $gamma > self::MAX_GAMMA){
            throw new \InvalidArgumentException("Invalid gamma value");
        }
        $this->gamma = $gamma;
    }

    /**
     * @param string $value
     */
    protected function _initFromString($value)
    {
        if(!is_numeric($value)){
            throw new \InvalidArgumentException("Gamma value expected");
        }
        $this->setGamma((float)$value);
    }
}
