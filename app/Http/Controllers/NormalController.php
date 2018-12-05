<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;

// Eloquent Models Shortcuts
use App\User;
use App\PendingPublisher;

class NormalController extends Controller
{
    public function index()
    {
        $monthList = $this->getMonthList(null);
        return view('user.index', compact('monthList'));
    }

    public function sign_in(Request $rq) 
    {
        $credentials = $rq->only('email', 'password');
        
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'status' => 1])) {
            // Authentication passed...
            return back()->with(['msg_success' => 'Successfully signed in!', 'msg_class' => 'success']);
        } else {
            return back()->with(['msg_fail' => 'Wrong email/password combination!<br>Please try again.', 'msg_class' => 'error']);
        }
    }

    public function sign_up(Request $rq)
    {
        try {
            $user = new User;
            $user->email        = $rq->user_email;
            $user->password     = bcrypt($rq->user_pass);
            $user->first_name   = $rq->user_first;
            $user->last_name    = $rq->user_last;
            $user->phone_number = $rq->user_phone;
            $user->birth_date   = $rq->user_birth;
            $user->status       = 1;
            $user->save();

            $pending = new PendingPublisher;
            $pending->user_id = $user->id;
            $pending->save();

            // Log user in to the system automatically
            Auth::login($user);

            return back()->with(['msg_success' => 'Successfully signed up!', 'msg_class' => 'success']);
        } catch (Exception $e) {
            return back()->with(['msg_fail' => 'Failed to sign up!<br>Please try again.', 'msg_class' => 'error']);
        }
    }

    public function sign_out()
    {
        try {
            Auth::logout();
            return redirect()->route('user-index')->with(['msg_success' => 'Successfully signed out!<br>See you again next time.', 'msg_class' => 'success']);
        } catch (Exception $e) {
            return back()->with(['msg_fail' => 'Failed to sign out!<br>Please try again.', 'msg_class' => 'error']);
        }
    }

    public function redirectToProvider()
    {
        // 
    }

    public function handleProviderCallback()
    {
        //
    }
}