<?php

namespace Tinyga\ImageOptimizerClient\Image;

use DateTime;
use Exception;
use JsonSerializable;
use ReflectionClass;
use ReflectionException;

abstract class JsonSerializableObject implements JsonSerializable
{

    /**
     * @param array $json_data
     *
     * @return $this
     * @throws ReflectionException
     * @throws Exception
     */
    public static function createFromJSON(array $json_data)
    {
        $ref = new ReflectionClass(static::class);
        /** @var JsonSerializableObject $object */
        $object = $ref->newInstanceWithoutConstructor();
        $object->jsonDeserialize($json_data);

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $output = [];
        foreach (get_object_vars($this) as $property => $value) {
            if ($property[0] === '_') {
                continue;
            }
            $output[$property] = $this->jsonSerializeProperty($property, $value);
        }

        return $output;
    }

    /**
     * @param array $values
     *
     * @throws Exception
     */
    public function jsonDeserialize(array $values)
    {
        foreach ($values as $property => $value) {
            if (!property_exists($this, $property) || $property[0] === '_') {
                continue;
            }
            $this->{$property} = $this->jsonDeserializeProperty($property, $value);
        }
    }

    /**
     * @param $property
     * @param $value
     *
     * @return mixed|string
     */
    protected function jsonSerializeProperty($property, $value)
    {
        if ($value instanceof DateTime) {
            return $value->format(DATE_ATOM);
        }

        if ($value instanceof JsonSerializable) {
            return $value->jsonSerialize();
        }

        return $value;
    }

    /**
     * @param $property
     * @param $value
     *
     * @return DateTime|null
     * @throws Exception
     */
    protected function jsonDeserializeProperty($property, $value)
    {
        if (preg_match('~_?(date|when|datetime)_?~', $property)) {
            if (!$value) {
                return null;
            }

            return new DateTime($value);
        }

        return $value;
    }
}