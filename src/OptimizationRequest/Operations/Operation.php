<?php
namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

abstract class Operation implements \JsonSerializable
{
    /**
     * @return string
     */
    abstract function getOperationName();

    public function validate()
    {
        $operation = $this->getOperationName();
        $errors = [];
        foreach(get_object_vars($this) as $property => $value){
            try {
                $this->validateProperty($property, $value);
            } catch(\Exception $e){
                $errors[$property] = "[{$operation}]: {$e->getMessage()}";
            }
        }

        if($errors){
            throw new \RuntimeException(implode("\n", $errors));
        }
    }

    protected function validateProperty($property, $value)
    {
        if($value === null){
            throw new \InvalidArgumentException("Value of '{$property}' is not defined");
        }
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}