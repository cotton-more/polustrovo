<?php

namespace App;

use Carbon\Carbon;

class ScreenshotsDaily
{
    private $attributes = [];

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function ids()
    {
        return explode(',', $this->attributes['ids']);
    }

    public function date()
    {
        return Carbon::createFromFormat('Y-m-d', $this->attributes['date'])->startOfDay();
    }

    public function count()
    {
        return (int) $this->attributes['count'];
    }
}