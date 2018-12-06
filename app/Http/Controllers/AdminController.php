<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Auth;

// Eloquent Models Shortcuts
use App\Admin;
use App\User;
use App\Article;
use App\Category;
use App\EditedArticle;
use App\PendingPublisher;
use App\TempCategory;

class AdminController extends Controller
{
    public function index()
    {
    	return view('admin.index');
    }


    // Sign In Functions
    public function sign_in_view()
    {
    	return view('admin.signin');
    }

    public function sign_in(Request $rq)
    {   
        $admin = Admin::where('username', $rq->username)->where('password', $rq->password)->first();
        
        if($admin) {
            return redirect()->route('admin-dashboard');
        } else {
            return back()->with(['msg_status' => 'Wrong username/password combination!<br>Please try again.', 'msg_class' => 'error']);
        }
    }


    // Categories Functions
    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function create_category_view()
    {
        return view('admin.create_category');
    }

    public function category_store(Request $rq)
    {   
        // Will optimize this bulk of code later
        try {

            if(!$rq->cat_edit) {
                $category_exist = Category::where('category_name', $rq->cat_name)->first();
                if ($category_exist)
                    return back()->with(['msg_status' => $rq->cat_name.' category already exists!<br>Please enter a different category name.']);
            }

            if($rq->cat_edit) {
                $category = Category::find($rq->cat_id);
            } else {
                $category = new Category();
            }

            $category->category_name = $rq->cat_name;
            $category->category_desc = $rq->cat_desc;
            
            if(!$rq->cat_edit || ($rq->cat_edit && $rq->file('cat_img'))) {
                if($rq->file('cat_img')) {
                    $image = $rq->file('cat_img');
                    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = 'admin/categories/';
                    $image->move($destinationPath, $input['imagename']);
                    $category->category_img  = $input['imagename'];
                } else {
                    $category->category_img  = 'no-img.png';
                }
            }

            $category->save();

            /**
             * if category is requested
             * if to set article to new category id & approve it
             */
            if ($rq->is_cat_req) 
            {
                $currentNewArticle = Article::find($rq->article_id);
                $currentNewArticle->category_id = $category->id;
                if ($rq->chk_set_approve)
                {
                    $currentNewArticle->verified = 1;
                    $currentNewArticle->published = 1;
                }
                $currentNewArticle->save();

                $tempCategory = TempCategory::where('article_id', $rq->article_id)->first();
                $tempCategory->status = 1;
                $tempCategory->save();

                if ($rq->chk_set_approve)
                    return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully added new category and approved the article.']);
                else
                    return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully added new category.']);
            }

            if($rq->cat_edit)
                return redirect()->route('admin-category')->with(['msg_class' => 'success', 'msg_status' => 'Successfully edited '.$rq->cat_name.' category.']);
            else
                return redirect()->route('admin-category')->with(['msg_class' => 'success', 'msg_status' => 'Successfully added '.$rq->cat_name.' category.']);

        } catch (Exception $e) {
            return redirect()->route('admin-category')->with(['msg_class' => 'error', 'msg_status' => 'An unexpected error has occured. Please try again.']);
        }
    }

    public function edit_category_view(Request $rq)
    {
        $category = Category::find($rq->cat_id);

        return view('admin.edit_category', compact('category'));
    }

    public function category_delete($id)
    {
        try {
            $category = Category::find($id);
            $name = $category->category_name;

            if ($category->articles->count() > 0)
                return back()->with(['msg_class' => 'error', 'msg_status' => $name.' is not removable.<br>There are articles associated to it.']);
            else 
            {
                $category->delete();
                return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully removed '.$name.' category.']);
            }
            
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_status' => 'Failed to remove category. Please try again.']);
        }
    }

    // Publishers Functions
    public function publishers()
    {
        $users = PendingPublisher::join('users', 'users.id', 'pending_publishers.user_id')->get();
        return view('admin.publishers', compact('users'));
    }

    public function approve_publisher($id)
    {
        try {
            $user = User::find($id);
            $user->publisher = 1;
            $user->save();

            $name = $user->first_name.' '.$user->last_name;

            $pending = PendingPublisher::where('user_id', $id)->first();
            $pending->pending_status = 1;
            $pending->save();

            return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully approved <span class="fw-600">'.$name.'</span> as a publisher.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_status' => 'Failed to approve '.$name.' as a publisher.<br>Please try again.']);
        }
    }

    public function reject_publisher($id)
    {
        try {
            $user = User::find($id);
            $name = $user->first_name.' '.$user->last_name;

            $pending = PendingPublisher::where('user_id', $id)->first();
            $pending->pending_status = -1;
            $pending->save();

            return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully rejected <span class="fw-600">'.$name.'</span> as a publisher.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_status' => 'Failed to reject '.$name.' as a publisher.<br>Please try again.']);
        }
    }


    // Articles Function
    public function articles()
    {
        $query = Article::query();
        $query = $query->leftJoin('temp_categories', function($join) {
            $join
                ->on('temp_categories.article_id', '=', 'articles.id')
                ->where('temp_categories.status', '=', "0") //0 indicates to be approve by admin
                ->where('articles.category_id', '=', "0"); //0 indicates to be approve by admin
        });
        $query = $query->select('articles.*', 'temp_categories.temp_category_name');

        $articles = $query->get();
        $categories = Category::orderBy('category_name', 'asc')->get();

        return view('admin.articles', compact('articles', 'categories'));
    }

    public function edited_articles()
    {
        $articles = EditedArticle::join('articles', 'articles.id', 'edited_articles.article_id')
            ->select('articles.article_title', 'articles.author', 'edited_articles.*')
            ->get();

        return view('admin.edited_articles', compact('articles'));
    }

    public function article_content($id)
    {
        $article = Article::find($id);
        $link_preview = $this->getLinkPreview($article->article_link);
        return view('admin.view_article', compact('article', 'link_preview'));
    }

    public function edited_article_content($id)
    {
        $article = EditedArticle::where('edited_articles.id', $id)->join('articles', 'articles.id', 'edited_articles.article_id')
            ->select('articles.article_title', 'edited_articles.*')
            ->first();

        return view('admin.view_article', compact('article'));
    }

    public function approve_article(Request $rq)
    {
        try {
            $article_id = $rq->selected_article_id;
            $category_id = $rq->selected_category_id;

            $article = Article::find($article_id);
            $article->verified = 1;
            $article->published = 1;
            $article->category_id = $category_id;
            $article->save();

            $tempCategory = TempCategory::where('article_id', $article_id)->first();
            if ($tempCategory)
            {
                $tempCategory->status = 1;
                $tempCategory->save();
            }

            return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully approved article.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_status' => 'Failed to approve article.<br>Please try again.']);
        }
    }

    public function reject_article($id)
    {
        try {
            $article = Article::find($id);
            $article->verified = -1;
            $article->save();

            return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully rejected article.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_status' => 'Failed to reject article.<br>Please try again.']);
        }
    }

    public function approve_edited_article($id)
    {
        try {
            $edited = EditedArticle::find($id);
            $edited->verified = 1;
            $edited->save();

            $article = Article::find($edited->article_id);
            $article->article_text  = $edited->article_text;
            $article->article_link  = $edited->article_link;
            $article->save();

            return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully approved edited article.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_status' => 'Failed to approve edited article.<br>Please try again.']);
        }
    }

    public function reject_edited_article($id)
    {
        try {
            $edited = EditedArticle::find($id);
            $edited->verified = -1;
            $edited->save();

            return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully rejected edited article.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_status' => 'Failed to reject edited article.<br>Please try again.']);
        }
    }
}
