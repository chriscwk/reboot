<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'NormalController@index');

Route::post('/signin', 'NormalController@sign_in');
Route::post('/signup', 'NormalController@sign_up');
Route::get('/signout', 'NormalController@sign_out');

Route::get('/administrator', 'AdminController@sign_in_view');
Route::post('/administrator', 'AdminController@sign_in');
Route::get('/administrator/dashboard', 'AdminController@index');
