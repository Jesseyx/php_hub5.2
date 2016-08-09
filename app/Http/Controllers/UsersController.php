<?php

namespace App\Http\Controllers;

use App\Phphub\Github\GithubUserDataReader;
use App\Reply;
use App\Topic;
use App\User;
use Cache;
use Illuminate\Http\Request;

use App\Http\Requests;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function show($id)
    {
        $user    = User::findOrFail($id);
        $topics  = Topic::whose($user->id)->recent()->limit(10)->get();
        $replies = Reply::whose($user->id)->recent()->limit(10)->get();

        return view('users.show', compact('user', 'topics', 'replies'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit', compact('user', 'topics', 'replies'));
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $data = $request->only('github_name', 'real_name', 'city', 'company', 'twitter_account', 'personal_website', 'introduction');

        $user->update($data);

        return redirect(route('users.show', $id));
    }

    public function blocking($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = $user->is_banned == 'yes' ? 'no' : 'yes';
        $user->save();

        return redirect(route('users.show', $id));
    }

    public function githubCard()
    {
        return view('users.github-card');
    }

    public function githubApiProxy($username)
    {
        $cache_name = 'github_api_proxy_user_' . $username;
        return Cache::remember($cache_name, 1440, function () use ($username) {
            $result = (new GithubUserDataReader())->getDataFromUserName($username);
            return response()->json($result);
        });
    }

    public function replies($id)
    {
        $user = User::findOrFail($id);
        $replies = Reply::whose($user->id)->recent()->paginate(15);

        return view('users.replies', compact('user', 'replies'));
    }

    public function topics($id)
    {
        $user = User::findOrFail($id);
        $topics = Topic::whose($user->id)->recent()->paginate(15);

        return view('users.topics', compact('user', 'topics'));
    }

    public function favorites($id)
    {
        $user = User::findOrFail($id);
        $topics = $user->favoriteTopics()->paginate(15);

        return view('users.favorites', compact('user', 'topics'));
    }

    public function refreshCache($id)
    {
        $user = User::findOrFail($id);

        $user_info = (new GithubUserDataReader())->getDataFromUserName($user->github_name);

        // Refresh the avatar cache.
        $user->image_url = $user_info['avatar_url'];
        // 缓存头像并保存
        $user->cacheAvatar();

        return redirect(route('users.edit', $id));
    }
}
