<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 17.03.17
 * Time: 21:10
 */

namespace App;


use Carbon\Carbon;

class Screenshot
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

    public function id()
    {
        return $this->attributes['screenshot_id'];
    }

    /**
     * @return Carbon
     */
    public function createdAt()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at']);

        return $date;
    }
}