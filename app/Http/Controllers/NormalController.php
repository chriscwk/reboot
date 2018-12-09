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

    // Sign In Functions
    public function admin_sign_in_view()
    {
        return view('admin.signin');
    }

    public function admin_sign_in(Request $rq)
    {   
        $credentials = $rq->only('username', 'password');
        
        if (Auth::guard('admin')->attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            // Authentication passed...
            return redirect()->route('admin-dashboard');
        } else {
            return back()->with(['msg_status' => 'Wrong username/password combination!<br>Please try again.', 'msg_class' => 'error']);
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

    public function forget_pass_email($email)
    {
        try {
            $user = User::where('email', $email)->first();

            $name = $user->first_name." ".$user->last_name;

            $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
            $beautymail->send('emails.forget_pass', ['name' => $name, 'id' => $user->id], function($message) use($user, $name)
            {
                $message
                    ->from('reboot.ces3033@gmail.com', 'Reboot Admin')
                    ->to($user->email, $name)
                    ->subject('Password Recovery');
            });

            return back()->with(['msg_success' => 'Successfully sent password forget email! Please check your inbox.', 'msg_class' => 'success']);
        } catch(Exception $e) {
            return back()->with(['msg_fail' => 'Failed to send forget password email.', 'msg_class' => 'error']);
        }
    }

    public function reset_pass($id)
    {
        $userID = $id;
        return view('user.reset_pass', compact('userID'));
    }

    public function change_pass(Request $rq)
    {
        try {
            $user = User::find($rq->userID);
            $user->password = bcrypt($rq->password);
            $user->save();

            return redirect('/')->with(['msg_success' => 'Successfully reset password! Please login again.', 'msg_class' => 'success']);
        } catch(Exception $e) {
            return back()->with(['msg_fail' => 'Failed to reset password. Please try again.', 'msg_class' => 'error']);
        }
    }
}