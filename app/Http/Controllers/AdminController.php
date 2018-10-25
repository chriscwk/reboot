<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

// Eloquent Models Shortcuts
use App\User;
use App\Article;
use App\Category;
use App\EditedArticle;
use App\PendingPublisher;

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
        return redirect()->route('admin-dashboard');
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
            } else {
                $category_exist = false;
            }

            if(!$category_exist) {

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

                if($rq->cat_edit) {
                    return redirect()->route('admin-category')->with(['msg_class' => 'success', 'msg_status' => 'Successfully edited '.$rq->cat_name.' category.']);
                }

                return redirect()->route('admin-category')->with(['msg_class' => 'success', 'msg_status' => 'Successfully added '.$rq->cat_name.' category.']);
            } else {
                return back()->with(['msg_status' => $rq->cat_name.' category already exists!<br>Please enter a different category name.']);
            }
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

            $category->delete();

            return back()->with(['msg_class' => 'success', 'msg_status' => 'Successfully removed '.$name.' category.']);
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
        $articles = Article::all();
        return view('admin.articles', compact('articles'));
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
        return view('admin.view_article', compact('article'));
    }

    public function edited_article_content($id)
    {
        $article = EditedArticle::where('article_id', $id)->join('articles', 'articles.id', 'edited_articles.article_id')
            ->select('articles.article_title', 'edited_articles.*')
            ->first();

        return view('admin.view_article', compact('article'));
    }

    public function approve_article($id)
    {
        try {
            $article = Article::find($id);
            $article->verified = 1;
            $article->published = 1;
            $article->save();

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
