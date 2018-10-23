<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
    	return view('admin.index');
    }

    public function sign_in_view()
    {
    	return view('admin.signin');
    }

    public function sign_in(Request $rq)
    {
        return view('admin.index');
    }
}
