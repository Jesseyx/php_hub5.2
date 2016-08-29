<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use App\Phphub\Github\GithubUserDataReader;
use App\Phphub\Handler\Exception\ImageUploadException;
use Auth;
use Cache;
use Illuminate\Http\Request;

use App\Http\Requests;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index()
    {
        $users = User::recent()->take(48)->get();

        return view('users.index', compact('users'));
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

    public function update($id, Requests\UpdateUserRequest $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        try {
            $request->performUpdate($user);
            // 发送通知
            flash(lang('Operation succeeded.'), 'success');
        } catch (ImageUploadException $e) {
            flash(lang($e->getMessage()), 'error');
        }

        return redirect(route('users.edit', $id));
    }

    public function blocking($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = $user->is_banned == 'yes' ? 'no' : 'yes';
        $user->save();

        // 用户被屏蔽后屏蔽用户所有内容，解封时解封所有内容
        $user->topics()->update(['is_blocked' => $user->is_banned]);
        $user->replies()->update(['is_blocked' => $user->is_banned]);

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

    public function following($id)
    {
        $user = User::findOrFail($id);
        $users = $user->followings()->orderBy('id', 'desc')->paginate(15);

        return view('users.following', compact('user', 'users'));
    }

    public function votes($id)
    {
        $user = User::findOrFail($id);
        // dd($user->votedTopics());
        $topics = $user->votedTopics()->orderBy('pivot_created_at', 'desc')->paginate(15);

        return view('users.votes', compact('user', 'topics'));
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

    public function editAvatar($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit_avatar', compact('user'));
    }

    public function updateAvatar($id, Request $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        if ($file = $request->file('avatar')) {
            try {
                $user->updateAvatar($file);
                flash(lang('Update Avatar Success'), 'success');
            } catch (ImageUploadException $e) {
                flash($e->getMessage(), 'error');
            }
        } else {
            flash(lang('Update Avatar Failed'), 'error');
        }

        return redirect(route('users.edit_avatar', $id));
    }

    public function editEmailNotify($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit_email_notify', compact('user'));
    }

    public function updateEmailNotify($id, Request $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $user->email_notify_enabled = $request->email_notify_enabled == 'on' ? 'yes' : 'no';
        $user->save();

        flash(lang('Operation succeeded.'), 'success');
        return redirect(route('users.edit_email_notify', $id));
    }

    public function editSocialBinding($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit_social_binding', compact('user'));
    }

    public function doFollow($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->isFollowing($id)) {
            Auth::user()->unfollow($id);
            $user->decrement('follower_count', 1);
        } else {
            Auth::user()->follow($id);
            $user->increment('follower_count', 1);
            app('App\Phphub\Notification\Notifier')->newFollowNotify(Auth::user(), $user);
        }

        flash(lang('Operation succeeded.'), 'success');
        return redirect(route('users.show', $id));
    }
}
