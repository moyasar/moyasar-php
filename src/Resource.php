<?php

namespace Moyasar;

abstract class Resource
{
    /**
     * Properties to skip during serialization
     *
     * @var array
     */
    protected $skipProps = [];

    /**
     * Converts snake_case to camelCase
     * 
     * @param string $name
     * @return string
     */
    protected static function snakeToCamel($name) {
        $parts = explode('_', strtolower($name));
        return $parts[0] . implode('', array_map('ucfirst', array_splice($parts, 1)));
    }

    /**
     * Converts camelCase to snake_case
     *
     * Thanks Laravel <3
     *
     * @param string $name
     * @return string
     */
    protected static function camelToSnake($name) {
        return strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', $name));
    }

    /**
     * Transform a basic type to a complex one
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected static function transform($key, $value)
    {
        return $value;
    }

    /**
     * Create an instance from array
     *
     * @param array $items
     * @return Resource
     */
    public static function fromArray($items)
    {
        $class = get_called_class();

        /** @var Resource $instance */
        $instance = new $class;

        $instance->updateFromArray($items);

        return $instance;
    }

    /**
     * Creates a Sadad instance using provided data
     *
     * @param string $json
     * @return self
     */
    public static function fromJson($json)
    {
        return static::fromArray(json_decode($json, true));
    }

    /**
     * Transform instance property (complex) to a basic type
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected static function transformBack($key, $value)
    {
        return $value;
    }

    /**
     * Update the current instance from an array
     *
     * @param array $items
     */
    protected function updateFromArray($items)
    {
        foreach ($items as $key => $value) {
            if (array_intersect([$key], $this->skipProps)) {
                continue;
            }

            try {
                $this->{static::snakeToCamel($key)} = static::transform($key, $value);
            } catch (\Exception $_) {}
        }
    }

    /**
     * Convert the current instance to an array of basic types
     *
     * @return array
     */
    public function toSnakeArray()
    {
        $instanceProps = get_object_vars($this);

        $data = [];

        foreach ($instanceProps as $key => $value) {
            if ($key == 'skipProps') {
                continue;
            }

            if (array_intersect([$key], $this->skipProps)) {
                continue;
            }

            $data[static::camelToSnake($key)] = static::transformBack($key, $value);
        }

        return $data;
    }

    /**
     * Convert the current instance to a JSON string
     *
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toSnakeArray());
    }
}