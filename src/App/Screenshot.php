<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 17.03.17
 * Time: 21:10
 */

namespace App;


use App\Entity\Entity;
use Carbon\Carbon;

class Screenshot extends Entity
{


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