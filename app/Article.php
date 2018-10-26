<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $casts = [
        'created_at' => 'date:d M Y H:i',
    ];
}
