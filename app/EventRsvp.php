<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventRsvp extends Model
{
    public function username()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
