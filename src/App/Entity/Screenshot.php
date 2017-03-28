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

    /**
     * @return Carbon|null
     */
    public function shootedAt()
    {
        $date = null;

        if ($shootedAt = $this->attr('shooted_at')) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $shootedAt);
        }

        return $date;
    }
}