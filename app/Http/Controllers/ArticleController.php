<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

// Eloquent Models
use App\Category;
use App\Article;
use App\EditedArticle;
use App\FavoriteArticle;
use App\Comment;
use App\ArticlesRatings;
use App\TempCategory;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::where('user_id', \Auth::user()->id)->orderBy('created_at', 'DESC')->get();

        return view('user.articles', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('category_name', 'asc')->get();

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

            $isOtherCategory = $rq->cat_id == null && $rq->other_category != null;

            $article = new Article;
            $article->user_id       = \Auth::user()->id;
            $article->article_title = $rq->article_headline;
            $article->article_text  = $rq->article_html;
            $article->article_link  = $rq->article_link;
            $article->author        = \Auth::user()->first_name.' '.\Auth::user()->last_name;

            /**
             * 1. Put other categories into '0' id
             * 2. Insert into temp_categories
             * 3. Allow admins to classify them properly / approve the requested category
             */
            $article->category_id   = $isOtherCategory ? 0 : $rq->cat_id;

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

            if ($isOtherCategory) 
            {
                $tempCategory = new TempCategory;
                $tempCategory->article_id           = $article->id;
                $tempCategory->user_id              = \Auth::user()->id;
                $tempCategory->status               = 0; //0 indicates category has not been looked into 
                $tempCategory->temp_category_name   = $rq->other_category;
                $tempCategory->save();
            }

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
            //fix article to certain category after being approved
            //disallow users from changing the category
            //$article->category_id     = $rq->cat_id; 
            $article->article_title     = $rq->article_headline;
            
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

    public function crawl_site(Request $rq)
    {
        if ($rq->site_url != "")
        {
            try
            {
                //$url = $this->addhttp($rq->site_url);

                // $screen_shot_json_data = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=".$url."&screenshot=true");
                // $screen_shot_result = json_decode($screen_shot_json_data, true);
                // $screen_shot = $screen_shot_result['screenshot']['data'];
                // $screen_shot = str_replace(array('_','-'), array('/', '+'), $screen_shot);
                // $screen_shot_image = "<img style='width: 100%' src=\"data:image/jpeg;base64,".$screen_shot."\"/>";

                //$screen_shot_image = 
                
                return $this->getLinkPreview($rq->site_url);
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

            $query = $query->leftJoin('favorite_articles', function($join) {
                $join
                    ->on('favorite_articles.article_id', '=', 'articles.id')
                    ->where('favorite_articles.user_id', '=', \Auth::check() ? \Auth::user()->id : "");
            });

            $query = $query->orderBy('articles.created_at', 'desc');
            $query = $query->limit($rowsPerQuery)->offset($current);
            $query = $query->select('articles.*', 'favorite_articles.user_id');

            $approvedArticles = $query->get();

            $approvedArticles = $approvedArticles->toArray();

            return $approvedArticles;
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to retrieve articles.']);
        }
    }

    // public function getArticleDetails($articleId, $articleName)
    public function getArticleDetails($articleId)
    {
        try
        {
            $query = Article::query();
            $query = $query->join('categories', 'categories.id', 'articles.category_id');
            $query = $query->where('verified', 1);
            $query = $query->where('published', 1);

            if ($articleId != null && $articleId != "")
                $query = $query->where('articles.id', $articleId);

            //removed articlename for the sake of easy retrieval
            // if ($articleName != null && $articleName != "")
            //     $query = $query->where('article_title', urldecode(str_replace('-', '+', $articleName)));

            $query = $query->leftJoin('favorite_articles', function($join) {
                $join
                    ->on('favorite_articles.article_id', '=', 'articles.id')
                    ->where('favorite_articles.user_id', '=', \Auth::check() ? \Auth::user()->id : "");
            });
            $query = $query->select('articles.*', 'categories.category_name', 'favorite_articles.user_id AS is_favorited');

            //Main details
            $articlePost = $query->firstOrFail();

            //Article's sub details
            $link_preview = $this->getLinkPreview($articlePost->article_link);
            $articleComments = $this->getArticleComments($articleId);
            $articleRatings = $this->getArticleRatings($articleId);
            $userRating = $this->getUserRating($articleId);

            return view('user.article_post', compact('articlePost', 'link_preview', 'articleComments', 'articleId', 'articleRatings', 'userRating'));
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to retrieve article.']);
        }
    }

    /**
     * To get article's comments
     * @param int $articleId
     * @return list of comments
     */
    private function getArticleComments($articleId)
    {
        
        return Comment::where('article_id', $articleId)->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * To get article's overall ratings
     * @param int $articleId
     * @return positive/negative ratings
     */
    private function getArticleRatings($articleId)
    {
        return DB::table('articles_ratings')
                ->select(DB::raw("COUNT(*) as total_ratings, IFNULL(SUM(CASE WHEN rating = '1' THEN 1 ELSE 0 END), 0) AS positive_ratings, IFNULL(SUM(CASE WHEN rating = '-1' THEN 1 ELSE 0 END), 0) AS negative_ratings"))
                ->where('article_id', '=', $articleId)
                ->first();
    }

    /**
     * To get user's rating
     * @param int $articleId
     * @return ratingLevel
     * Rating Level:
     * -1 = downvoted
     * 0 = neutral
     * 1 = upvoted
     */
    private function getUserRating($articleId)
    {
        $ratingLevel = 0;

        if (\Auth::check())
        {
            $userRating = ArticlesRatings::where('article_id', $articleId)->where('user_id', \Auth::user()->id)->first();
            if ($userRating != null)
                $ratingLevel = $userRating->rating;
        }

        return $ratingLevel;
    }

    public function favoriteArticle(Request $rq)
    {
        try
        {
            $favArticle = new FavoriteArticle;
            $favArticle->article_id = $rq->article_id;
            $favArticle->user_id = \Auth::user()->id;
            $favArticle->status = 1;
            $isFavorited = $rq->isFavorited === 'true'? true: false;

            if ($isFavorited)
            {
                $isExist = FavoriteArticle::where('article_id', $favArticle->article_id)->where('user_id', $favArticle->user_id)->first();
                if (!$isExist)
                    $favArticle->save();
            }
            else
            {
                $toDelete = FavoriteArticle::where('article_id', $favArticle->article_id)->where('user_id', $favArticle->user_id);
                $toDelete->delete();
            }
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to favorite the article.']);
        }
    }

    public function addComment(Request $rq)
    {
        try
        {
            $comment = new Comment;
            $comment->article_id    = $rq->article_id;
            $comment->user_id       = \Auth::user()->id;
            $comment->comment_text  = $rq->comment_area;
            $comment->created_by    = \Auth::user()->first_name.' '.\Auth::user()->last_name;

            $comment->save();
            return back()->with(['msg_class' => 'success', 'msg_success' => 'Your comment have been posted.']);
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to add comment.']);
        }
    }

    public function rateArticle(Request $rq)
    {
        try
        {
            $article_id = $rq->article_id;
            $is_rate = $rq->isRate === 'true'? true: false;
            $rating = $rq->rating === 'up' ? 1 : -1;

            $temp = ArticlesRatings::where('article_id', $article_id)->where('user_id', \Auth::user()->id)->first();
            $articleRating = $temp ? ArticlesRatings::find($temp->id) : null;

            if ($articleRating && $is_rate) //update existing rating 
            {
                $articleRating->rating = $rating;
                $articleRating->save();
            }
            else if ($articleRating && !$is_rate) //delete existing rating
            {
                $articleRating->rating = 0;
                $articleRating->save();
            }
            else if ($is_rate) //insert new record
            {
                $articleRating = new ArticlesRatings;
                $articleRating->article_id = $article_id;
                $articleRating->user_id = \Auth::user()->id;
                $articleRating->rating = $rating;
                $articleRating->save();
            }
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to add comment.']);
        }
    }
}
