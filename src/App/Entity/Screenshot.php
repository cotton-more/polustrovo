<?php

namespace App\Entity;

use Carbon\Carbon;

class Screenshot extends Entity
{
    public function id()
    {
        return $this->attr('screenshot_id');
    }

    /**
     * @return Carbon
     */
    public function createdAt()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->attr('created_at'));

        return $date;
    }
}