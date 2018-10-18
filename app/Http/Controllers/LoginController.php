<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $rq) 
    {
    	$credentials = $rq->only('user_email', 'user_pass');
       	
       	if (Auth::attempt($credentials)) {
            // Authentication passed...
            return view('user.index');
        }
    }
}
