<?php

namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations;

class OperationGrayscale extends Operation
{
    function getOperationType()
    {
        return Operations::OPERATION_GRAYSCALE;
    }


    /**
     * @param string $value
     */
    protected function _initFromString($value)
    {
    }
}
