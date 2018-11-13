<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $casts = [
        'event_start_time' => 'date:d M Y H:i',
        'event_end_time' => 'date:d M Y H:i',
    ];
}
