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
Route::get('/articles/create', 'ArticleController@create');
Route::get('/articles/{articleId}', 'ArticleController@getArticleDetails');
Route::post('/articles/getApprovedArticleByPage', 'ArticleController@getApprovedArticleByPage');
Route::post('/articles/favoriteArticle', 'ArticleController@favoriteArticle');
Route::post('/articles/store', 'ArticleController@store');
Route::post('/articles/edit/view', 'ArticleController@edit');
Route::post('/articles/edit/update', 'ArticleController@update');
Route::get('/articles/delete/{id}', 'ArticleController@destroy');
Route::post('/articles/crawl_site', 'ArticleController@crawl_site');
Route::post('/articles/addComment', 'ArticleController@addComment');
Route::post('/articles/rateArticle', 'ArticleController@rateArticle');

Route::get('/events', 'EventController@index')->name('events');
Route::get('/events/all', 'EventController@all_events');
Route::get('/events/create', 'EventController@create');
Route::post('/events/edit/view', 'EventController@edit');
Route::post('/events/getEvents', 'EventController@getEvents');
Route::post('/events/store', 'EventController@store');
Route::post('/events/getLatLong', 'EventController@getLatLong');
Route::get('/events/delete/{id}', 'EventController@destroy');
Route::get('/events/view/{id}', 'EventController@view_event');
Route::get('/events/rsvp/{id}', 'EventController@rsvp_event');

Route::get('login/facebook', 'LoginController@redirectToProvider');
Route::get('login/facebook/callback', 'LoginController@handleProviderCallback');

Route::get('/user-profile', 'UserController@index')->name('userprofile');
Route::post('/user-profile/update', 'UserController@update');

Route::get('/password/forget/{email}', 'NormalController@forget_pass_email');
Route::get('/password/reset/{id}', 'NormalController@reset_pass');
Route::post('/password/reset/store', 'NormalController@change_pass');

// Administrator Routes
Route::get('/admin/login', 'NormalController@admin_sign_in_view')->name('admin');
Route::post('/admin/login', 'NormalController@admin_sign_in');

Route::group(['guard' => ['admin']], function () {
    Route::get('/admin', 'AdminController@index')->name('admin-dashboard');

	Route::get('/admin/categories', 'AdminController@categories')->name('admin-category');
	Route::get('/admin/categories/create', 'AdminController@create_category_view');
	Route::post('/admin/categories/store', 'AdminController@category_store');
	Route::post('/admin/categories/edit', 'AdminController@edit_category_view');
	Route::get('/admin/categories/delete/{id}', 'AdminController@category_delete');

	Route::get('/admin/users/publishers', 'AdminController@publishers');
	Route::get('/admin/users/publishers/approve/{id}', 'AdminController@approve_publisher');
	Route::get('/admin/users/publishers/reject/{id}', 'AdminController@reject_publisher');

	Route::get('/admin/articles', 'AdminController@articles');
	Route::get('/admin/editedarticles', 'AdminController@edited_articles');
	Route::get('/admin/articles/view/{id}', 'AdminController@article_content');
	Route::get('/admin/articles/view/edited/{id}', 'AdminController@edited_article_content');
	//Route::get('/administrator/articles/approve/{id}', 'AdminController@approve_article');
	Route::post('/admin/articles/approve', 'AdminController@approve_article');
	Route::get('/admin/articles/reject/{id}', 'AdminController@reject_article');
	Route::get('/admin/articles/approve/edited/{id}', 'AdminController@approve_edited_article');
	Route::get('/admin/articles/reject/edited/{id}', 'AdminController@reject_edited_article');

	Route::get('/admin/logout', 'AdminController@admin_sign_out');
});
