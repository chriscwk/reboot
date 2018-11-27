<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $casts = [
        'created_at' => 'date:d M Y H:i',
    ];

    protected $appends = ['encoded_name'];

    public function getEncodedNameAttribute()
    {
        return str_replace('+', '-', urlencode($this->article_title));
    }

    public function favoriteArticles()
    {
        return $this->hasMany('App\FavoriteArticle');
    }
}
