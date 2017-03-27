<?php

namespace App\Entity;

class Entity
{
    private $attributes = [];

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function attr($name)
    {
        return isset($this->attributes) ? $this->attributes[$name] : null;
    }
}