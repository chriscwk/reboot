<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;

// Eloquent Models Shortcuts
use App\User;

class NormalController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function sign_in(Request $rq) 
    {
        $credentials = $rq->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return back()->with(['msg_success' => 'Successfully signed in!', 'msg_class' => 'success']);
        } else {
            return back()->with(['msg_fail' => 'Failed to sign in! Invalid credentials.', 'msg_class' => 'error']);
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
            $user->save();

            return back()->with(['msg_success' => 'Successfully signed up!<br>You may now sign in.', 'msg_class' => 'success']);
        } catch (Exception $e) {
            return back()->with(['msg_fail' => 'Failed to sign up!<br>Please try again.', 'msg_class' => 'error']);
        }
    }

    public function sign_out()
    {
        try {
            Auth::logout();
            return back()->with(['msg_success' => 'Successfully signed out!<br>See you again next time.', 'msg_class' => 'success']);
        } catch (Exception $e) {
            return back()->with(['msg_fail' => 'Failed to sign out!<br>Please try again.', 'msg_class' => 'error']);
        }
    }
}
