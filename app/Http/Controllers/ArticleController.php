<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Eloquent Models
use App\Category;
use App\Article;
use App\EditedArticle;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::where('user_id', \Auth::user()->id)->get();

        return view('user.articles', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        $backgrounds = ['bg1.jpg', 'bg2.jpg', 'bg3.jpg', 'bg4.jpg'];
        $index = mt_rand(0,3);
        $sample_image = $backgrounds[$index];

        if(count($categories) > 0) {
            return view('user.create_article', compact('categories', 'sample_image'));
        } else {
            return redirect()->route('articles')->with(['msg_class' => 'error', 'msg_fail' => 'It seems like there are no categories available.<br>Every article should be categorized, right? ;)<br><br>Standby while some categories magically appear.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $rq)
    {
        try {
            $article = new Article;
            $article->user_id       = \Auth::user()->id;
            $article->category_id   = $rq->cat_id;
            $article->article_title = $rq->article_headline;
            $article->article_text  = $rq->article_html;
            $article->article_link  = $rq->article_link;
            $article->author        = \Auth::user()->first_name.' '.\Auth::user()->last_name;

            if($rq->file('article_img')) {
                $image = $rq->file('article_img');
                $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = 'user/articles/';
                $image->move($destinationPath, $input['imagename']);
                $article->article_img  = $input['imagename'];
            } else {
                $article->article_img  = $rq->sample_image;
            }

            $article->save();

            return redirect()->route('articles')->with(['msg_class' => 'success', 'msg_success' => 'You have successfully submitted your article!<br>Please wait for it to be verified by our hardworking admins.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to submit article. Please try again.']);
        }
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $rq)
    {
        $article = Article::find($rq->article_id);
        $categories = Category::all();

        return view('user.edit_article', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $rq)
    {
        try {
            $article = Article::find($rq->article_id);
            $article->category_id   = $rq->cat_id;
            $article->article_title = $rq->article_headline;
            
            if($rq->publish_id) {
                $article->published     = $rq->publish_id;
            }

            if(($rq->link_changed || $rq->html_changed) && $article->verified != 0) {
                $edited = new EditedArticle;
                $edited->article_id    = $article->id;
                $edited->article_text  = $rq->article_html;
                $edited->article_link  = $rq->article_link;
                $edited->save();
            } else {
                $article->article_text = $rq->article_html;
                $article->article_link = $rq->article_link;
            }

            if($rq->file('article_img')) {
                $image = $rq->file('article_img');
                $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = 'user/articles/';
                $image->move($destinationPath, $input['imagename']);
                $article->article_img  = $input['imagename'];
            }

            $article->save();

            return redirect()->route('articles')->with(['msg_class' => 'success', 'msg_success' => 'Successfully edited article.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to edit article. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $article = Article::find($id);
            $article->delete();

            return back()->with(['msg_class' => 'success', 'msg_success' => 'Successfully removed article.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to remove article. Please try again.']);
        }
    }
    
    //TODO for link preview
    //NOT USED FOR NOW
    public function crawl_site(Request $rq)
    {
        if ($rq->site_url != "")
        {
            try
            {
                $website = file_get_contents($rq->site_url);
                return $website;
            }
            catch (Exception $e) 
            {
                return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to crawl onto specified site. Please try again.']);
            }
        }
        else 
            return "";
    }

    public function getApprovedArticleByPage(Request $rq)
    {
        try
        {
            $rowsPerQuery = config("app.home_articles_per_page");
            $current = $rq->current;
            $categoryId = $rq->categoryId;
    
            $query = Article::query();
            $query = $query->where('verified', 1);
            $query = $query->where('published', 1);

            if ($categoryId != null && $categoryId != "")
                $query = $query->where('category_id', $categoryId);

            $query = $query->orderBy('created_at', 'desc');
            $query = $query->limit($rowsPerQuery)->offset($current);

            $approvedArticles = $query->get();

            $approvedArticles = $approvedArticles->toArray();

            return $approvedArticles;
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to retrieve articles.']);
        }
    }

    public function getArticleDetails($articleId, $articleName)
    {
        try
        {
            $query = Article::query();
            $query = $query->join('categories', 'categories.id', 'articles.category_id');
            $query = $query->where('verified', 1);
            $query = $query->where('published', 1);

            if ($articleId != null && $articleId != "")
                $query = $query->where('articles.id', $articleId);
            if ($articleName != null && $articleName != "")
                $query = $query->where('article_title', urldecode($articleName));

            $articlePost = $query->firstOrFail();
            
            return view('user.article_post', compact('articlePost'));
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to retrieve article.']);
        }
    }

}
