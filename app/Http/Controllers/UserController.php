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
use App\EventRsvp;

class UserController extends Controller
{
    public function index()
    {
        $userProfile = User::find(\Auth::user()->id);
        
        $query = FavoriteArticle::query();
        $query = $query->where('favorite_articles.user_id', \Auth::user()->id);
        $query = $query->join('articles', 'articles.id', '=', 'favorite_articles.article_id');
        $query = $query->orderBy('favorite_articles.created_at', 'desc');
        $query = $query->select('articles.*');
        $favoritedArticles = $query->paginate(5, ['*'], config("app.favorite_article_pagination"));
        
        $query = EventRsvp::query();
        $query = $query->where('event_rsvps.user_id', \Auth::user()->id);
        $query = $query->join('events', 'events.id', '=', 'event_rsvps.event_id');
        $query = $query->orderBy('events.event_start_time', 'desc');
        $query = $query->select('events.*');
        $attendedEvents = $query->paginate(5, ['*'], config("app.attended_meetups_pagination"));

        return view('user.user_profile', compact('userProfile', 'favoritedArticles', 'attendedEvents'));
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