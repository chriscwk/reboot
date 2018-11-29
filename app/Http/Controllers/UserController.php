<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;

// Eloquent Models Shortcuts
use App\User;
use App\PendingPublisher;
use App\Article;
use App\FavoriteArticle;

class UserController extends Controller
{
    public function index()
    {
        $userProfile = User::find(\Auth::user()->id);
        
        $query = FavoriteArticle::query();
        $query = $query->where('favorite_articles.user_id', \Auth::user()->id);
        $query = $query->join('articles', 'articles.id', '=', 'favorite_articles.article_id');
        $query = $query->select('articles.*');
        $favoritedArticles = $query->paginate(3);

        return view('user.user_profile', compact('userProfile', 'favoritedArticles'));
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
            $user = User::find($rq->user_id);
            $user->first_name   = $rq->user_first_name;
            $user->last_name    = $rq->user_last_name;
            $user->phone_number = $rq->user_contact_number;
            $user->birth_date   = $rq->user_birth_date;

            $user->save();

            return redirect()->route('userprofile')->with(['msg_class' => 'success', 'msg_success' => 'Successfully edited user profile.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to edit user profile. Please try again.']);
        }
    }
}