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

// Guest/User Routes
Route::get('/', 'NormalController@index')->name('user-index');

Route::post('/signin', 'NormalController@sign_in');
Route::post('/signup', 'NormalController@sign_up');
Route::get('/signout', 'NormalController@sign_out');

Route::get('/categories', 'CategoryController@index')->name('category-index');
Route::get('/categories/{categoryId}', 'CategoryController@getCategoryDetails');

Route::get('/articles', 'ArticleController@index')->name('articles');
Route::get('/articles/{articleId}/{articleName}', 'ArticleController@getArticleDetails');
Route::get('/articles/create', 'ArticleController@create');
Route::post('/articles/getApprovedArticleByPage', 'ArticleController@getApprovedArticleByPage');
Route::post('/articles/store', 'ArticleController@store');
Route::post('/articles/edit/view', 'ArticleController@edit');
Route::post('/articles/edit/update', 'ArticleController@update');
Route::get('/articles/delete/{id}', 'ArticleController@destroy');

Route::get('login/facebook', 'LoginController@redirectToProvider');
Route::get('login/facebook/callback', 'LoginController@handleProviderCallback');

// Administrator Routes
Route::get('/administrator', 'AdminController@sign_in_view');
Route::post('/administrator', 'AdminController@sign_in');

Route::get('/administrator/dashboard', 'AdminController@index')->name('admin-dashboard');

Route::get('/administrator/categories', 'AdminController@categories')->name('admin-category');
Route::get('/administrator/categories/create', 'AdminController@create_category_view');
Route::post('/administrator/categories/store', 'AdminController@category_store');
Route::post('/administrator/categories/edit', 'AdminController@edit_category_view');
Route::get('/administrator/categories/delete/{id}', 'AdminController@category_delete');

Route::get('/administrator/users/publishers', 'AdminController@publishers');
Route::get('/administrator/users/publishers/approve/{id}', 'AdminController@approve_publisher');
Route::get('/administrator/users/publishers/reject/{id}', 'AdminController@reject_publisher');

Route::get('/administrator/articles', 'AdminController@articles');
Route::get('/administrator/editedarticles', 'AdminController@edited_articles');
Route::get('/administrator/articles/view/{id}', 'AdminController@article_content');
Route::get('/administrator/articles/view/edited/{id}', 'AdminController@edited_article_content');
Route::get('/administrator/articles/approve/{id}', 'AdminController@approve_article');
Route::get('/administrator/articles/reject/{id}', 'AdminController@reject_article');
Route::get('/administrator/articles/approve/edited/{id}', 'AdminController@approve_edited_article');
Route::get('/administrator/articles/reject/edited/{id}', 'AdminController@reject_edited_article');
