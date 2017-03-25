<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 23.03.17
 * Time: 20:44
 */

namespace App\Service\Browshot\Response;


class ScreenshotErrorResponse extends ScreenshotResponse
{
    public static function fromArray($data)
    {
        return new static($data);
    }
}