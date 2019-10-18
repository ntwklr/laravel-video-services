<?php

namespace Ntwklr\VideoServices\Services;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use JsonSerializable;

abstract class Service implements Arrayable, Jsonable, JsonSerializable
{
    abstract protected function requestData(string $url);
    abstract public function get();
    abstract public function transform(array $data);

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (! $key) {
            return;
        }

        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return (array) $this->attributes;
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return ! is_null($this->attributes[$key]);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
