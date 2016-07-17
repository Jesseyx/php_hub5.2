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
Route::get('/topics/{id}', 'TopicsController@show')->name('topics.show');
