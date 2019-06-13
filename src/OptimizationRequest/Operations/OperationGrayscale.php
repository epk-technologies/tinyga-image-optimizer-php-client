<?php

namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations;

class OperationGrayscale extends Operation
{
    function getOperationName()
    {
        return Operations::OPERATION_GRAYSCALE;
    }
}