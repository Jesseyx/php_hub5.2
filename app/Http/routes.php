<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

# ------------------ Page Route ------------------------
Route::get('/', 'PagesController@home')->name('home');
Route::get('/search', 'PagesController@search')->name('search');


# ------------------ Authentication ------------------------
Route::get('login', 'Auth\AuthController@login')->name('login');
Route::get('signup', 'Auth\AuthController@create')->name('signup');
Route::post('signup', 'Auth\AuthController@store');
Route::get('logout', 'Auth\AuthController@logout')->name('logout');

# ------------------ Topic ------------------------
Route::get('/topics', 'TopicsController@index')->name('topics.index');
Route::get('/topics/{id}', 'TopicsController@show')->name('topics.show');
Route::get('/topics/create', 'TopicsController@create')->name('topics.create');
Route::post('/topics', 'TopicsController@store')->name('topics.store');
Route::get('/topics/{id}/edit', 'TopicsController@edit')->name('topics.edit');
Route::post('/topics/{id}/append', 'TopicsController@append')->name('topics.append');

# ------------------ User stuff ------------------------
Route::group(['middleware' => 'auth'], function () {
    Route::get('/notifications/count', 'NotificationsController@count')->name('notifications.count');

    // 关注
    Route::post('/attentions/{id}', 'AttentionsController@createOrDelete')->name('attentions.createOrDelete');
    // 收藏
    Route::post('/favorites/{id}', 'FavoritesController@createOrDelete')->name('favorites.createOrDelete');
});

# ------------------ Replies ------------------------
Route::post('/replies', 'RepliesController@store')->name('replies.store');

# ------------------ Users ------------------------
Route::get('/users/{id}', 'UsersController@show')->name('users.show');
Route::get('/users/create', 'UsersController@create')->name('users.create');

# ------------------ Votes ------------------------
Route::group(['before' => 'auth'], function () {
    Route::post('/topics/{id}/upvote', 'TopicsController@upvote')->name('topics.upvote');
    Route::post('/topics/{id}/downvote', 'TopicsController@downvote')->name('topics.downvote');
});

# ------------------ Admin Route ------------------------
// 这里的 before 应该没什么作用了
Route::group(['before' => 'manage_topics'], function () {
    Route::post('topics/recommend/{id}', 'TopicsController@recommend')->name('topics.recommend');
    Route::post('topics/pin/{id}', 'TopicsController@pin')->name('topics.pin');
    Route::post('topics/sink/{id}', 'TopicsController@sink')->name('topics.sink');
    Route::delete('topics/delete/{id}', 'TopicsController@destroy')->name('topics.destroy');
});

# ------------------ Categories ------------------------
Route::get('/categories/{id}', 'CategoriesController@show')->name('categories.show');
