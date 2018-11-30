<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $casts = [
        'created_at' => 'date:d M Y H:i',
    ];

    protected $appends = ['encoded_name', 'logged_in'];

    public function getEncodedNameAttribute()
    {
        return str_replace('+', '-', urlencode($this->article_title));
    }

    public function getLoggedInAttribute()
    {
        return \Auth::check();
    }
}
