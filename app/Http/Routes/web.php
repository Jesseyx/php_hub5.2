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
Route::get('/about', 'PagesController@about')->name('about');
Route::get('/feed', 'PagesController@feed')->name('feed');
Route::get('/sitemap', 'PagesController@sitemap');
Route::get('/sitemap.xml', 'PagesController@sitemap');
Route::get('/hall_of_fames', 'PagesController@hallOfFames')->name('hall_of_fames');

# ------------------ Authentication ------------------------
Route::get('/login', 'Auth\AuthController@oauth')->name('login');
Route::get('/auth/oauth', 'Auth\AuthController@oauth')->name('auth.oauth');
Route::get('/auth/callback', 'Auth\AuthController@callback')->name('auth.callback');
Route::get('/verification/{token}', 'Auth\AuthController@getVerification')->name('verification');

Route::get('/signup', 'Auth\AuthController@create')->name('signup');
Route::post('/signup', 'Auth\AuthController@store');
Route::get('/logout', 'Auth\AuthController@logout')->name('logout');
Route::get('/user-banned', 'Auth\AuthController@userBanned')->name('user-banned');

# ------------------ Topic ------------------------
Route::get('/topics', 'TopicsController@index')->name('topics.index');
Route::get('/topics/{id}', 'TopicsController@show')->name('topics.show');
Route::get('/topics/create', 'TopicsController@create')->name('topics.create')->middleware('verified_email');
Route::post('/topics', 'TopicsController@store')->name('topics.store')->middleware('verified_email');
Route::get('/topics/{id}/edit', 'TopicsController@edit')->name('topics.edit');
Route::patch('/topics/{id}', 'TopicsController@update')->name('topics.update');
Route::post('/topics/{id}/append', 'TopicsController@append')->name('topics.append');

# ------------------ User stuff ------------------------
// Route::get('/users/{id}/refresh_cache', 'UsersController@refreshCache')->name('users.refresh_cache');
// Route::get('/users/{id}/favorites', 'UsersController@favorites')->name('users.favorites');
Route::get('/users/{id}/replies', 'UsersController@replies')->name('users.replies');
Route::get('/users/{id}/topics', 'UsersController@topics')->name('users.topics');
Route::get('/users/{id}/following', 'UsersController@following')->name('users.following');
Route::get('/users/{id}/followers', 'UsersController@followers')->name('users.followers');
Route::post('/users/follow/{id}', 'UsersController@doFollow')->name('users.doFollow');
Route::get('/users/{id}/votes', 'UsersController@votes')->name('users.votes');
Route::get('/users/{id}/edit_email_notify', 'UsersController@editEmailNotify')->name('users.edit_email_notify');
Route::post('/users/{id}/update_email_notify', 'UsersController@updateEmailNotify')->name('users.update_email_notify');
Route::get('/users/{id}/edit_social_binding', 'UsersController@editSocialBinding')->name('users.edit_social_binding');
Route::post('/users/regenerate_login_token', 'UsersController@regenerateLoginToken')->name('users.regenerate_login_token');

# ------------------ Users ------------------------
Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/{id}', 'UsersController@show')->name('users.show');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::get('/users/{id}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{id}', 'UsersController@update')->name('users.update');
Route::delete('/users/{id}', 'UsersController@destroy')->name('users.destroy');
Route::get('/users/{id}/edit_avatar', 'UsersController@editAvatar')->name('users.edit_avatar');
Route::patch('/users/{id}/update_avatar', 'UsersController@updateAvatar')->name('users.update_avatar');

# ------------------ Users 激活邮件------------------------
Route::get('/email-verification-required', 'UsersController@emailVerificationRequired')->name('email-verification-required');
Route::post('/users/send-verification-mail', 'UsersController@sendVerificationMail')->name('users.send-verification-mail');

// Route::get('/github-card', 'UsersController@githubCard')->name('users.github-card');
// Route::get('/github-api-proxy/users/{username}', 'UsersController@githubApiProxy')->name('users.github-api-proxy');

# ------------------ Replies ------------------------
Route::post('/replies', 'RepliesController@store')->name('replies.store')->middleware('verified_email');
Route::delete('/replies/delete/{id}', 'RepliesController@destroy')->name('replies.destroy')->middleware('auth');

# ------------------ Site ------------------------
Route::get('/sites', 'SitesController@index')->name('sites.index');

# ------------------ Categories ------------------------
Route::get('/categories/{id}', 'CategoriesController@show')->name('categories.show');

# ------------------ Upload image ------------------------
Route::post('/upload_image', 'TopicsController@uploadImage')->name('upload_image')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    // 主要是 ajax 提交的
    # ------------------ Notifications ------------------------
    Route::get('/notifications', 'NotificationsController@index')->name('notifications.index');
    Route::get('/notifications/count', 'NotificationsController@count')->name('notifications.count');

    // 关注
    // Route::post('/attentions/{id}', 'AttentionsController@createOrDelete')->name('attentions.createOrDelete');
    // 收藏
    // Route::post('/favorites/{id}', 'FavoritesController@createOrDelete')->name('favorites.createOrDelete');

    # ------------------ Votes ------------------------
    Route::post('/topics/{id}/upvote', 'TopicsController@upvote')->name('topics.upvote');
    Route::post('/topics/{id}/downvote', 'TopicsController@downvote')->name('topics.downvote');
    Route::post('/replies/{id}/vote', 'RepliesController@vote')->name('replies.vote');
});

# ------------------ Admin Route ------------------------
// 这里的 before 没什么作用了, before 是 4.2 的过滤器，5.2 已经被 middleware 替换了, 但是在控制器里面有权限控制 $this->authorize()
Route::group(['before' => 'manage_topics'], function () {
    Route::post('topics/recommend/{id}', 'TopicsController@recommend')->name('topics.recommend');
    Route::post('topics/pin/{id}', 'TopicsController@pin')->name('topics.pin');
    Route::post('topics/sink/{id}', 'TopicsController@sink')->name('topics.sink');
    Route::delete('topics/delete/{id}', 'TopicsController@destroy')->name('topics.destroy');
});

Route::group(['before' => 'manage_users'], function () {
    Route::post('/users/blocking/{id}', 'UsersController@blocking')->name('users.blocking');
});

# ------------------ Logs ------------------------
Route::group(['middleware' => ['auth', 'admin_auth']], function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

// Health Checking
Route::get('/heartbeat', function () {
    return \App\Models\Category::first();
});
