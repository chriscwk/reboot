<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Article;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    
    public function addhttp($url) 
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    public function getLinkPreview($url)
    {
        $url = $this->addhttp($url);
        return "<img style='width: 100%' src='"."https://image.thum.io/get/width/1920/crop/1080/".$url."'></img>";
    }

    /**
     * To retrieve all articles and group them by their created date into months/year
     * and also its corresponding number of articles
     * @return a list of month/year and its corresponding number of articles
     */
    public function getMonthList($categoryId)
    {
        if (!$categoryId)
            $monthList = Article::all()
            ->where('verified', 1)
            ->where('published', 1)
            ->sortByDesc('created_at')
            ->groupBy(function ($item, $key) {
                return $item->created_at->format('F, Y');
            });
        else 
            $monthList = Article::all()
            ->where('verified', 1)
            ->where('published', 1)
            ->where('category_id', $categoryId)
            ->sortByDesc('created_at')
            ->groupBy(function ($item, $key) {
                return $item->created_at->format('F, Y');
            });

        $monthList = $monthList->map(function ($item) {
            return $item->count();
        });

        return $monthList;
    }
}
