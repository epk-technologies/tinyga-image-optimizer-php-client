<?php
namespace Tinyga\ImageOptimizer\OptimizationRequest\Operations;

use RuntimeException;

abstract class Operation implements \JsonSerializable
{
    /**
     * @return string
     */
    abstract function getOperationName();

    /**
     * @throws RuntimeException
     */
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
            throw new RuntimeException(implode("\n", $errors));
        }
    }

    /**
     * @param string $property
     * @param mixed $value
     */
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

    /**
     * @param array $properties
     */
    public function initFromArray(array $properties)
    {
        foreach($properties as $property => $value){
            if(property_exists($this, $property)){
                $this->initProperty($property, $value);
            }
        }
    }

    /**
     * @param string $property
     * @param mixed $value
     */
    protected function initProperty($property, $value)
    {
        $setter_name = "set" . str_replace(' ', '',
                ucwords(
                    str_replace('_', ' ', $property)
                )
            );

        if(method_exists($this, $setter_name)){
            $this->{$setter_name}($value);
        } else {
            $this->{$property} = $value;
        }
    }

    /**
     * @param string $value
     */
    public function initFromString($value)
    {
        try {
            $this->_initFromString($value);
        } catch(\Exception $e){
            throw new \RuntimeException("Cannot initialize operation '{$this->getOperationName()}' [" . get_class($this) . "] from string - {$e->getMessage()}");
        }
    }

    /**
     * @param string $value
     */
    abstract protected function _initFromString($value);

}