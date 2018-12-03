<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Eloquent Models
use App\Category;
use App\Article;
use App\EditedArticle;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorisedArticles = Category::withCount(['articles' => function ($query) {
                                    $query->where('articles.verified', '=', '1')
                                    ->where('articles.published', '=', '1');
                                }])->orderBy('category_name', 'ASC')->get();

        return view('user.categories', compact('categorisedArticles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function getCategoryDetails($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        return view('user.categorised_articles', compact('category'));
    }
}
