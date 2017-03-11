<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $table = 'screenshot';

    protected $primaryKey = 'screenshot_id';

    protected $fillable = [
        'name', 'is_new', 'shooted_at',
    ];

    public $timestamps = false;
}