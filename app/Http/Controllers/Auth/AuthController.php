<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Traits\SocialiteHelper;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Phphub\Listeners\UserCreatorListener;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Socialite;
use Session;

class AuthController extends Controller implements UserCreatorListener
{
    use SocialiteHelper;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function create()
    {
        if (!Session::has('oauthData')) {
            return redirect(route('login'));
        }

        $oauthData = array_merge(Session::get('oauthData'), Session::get('_old_input', []));
        return view('auth.signupconfirm', compact('oauthData'));
    }

    public function store(StoreUserRequest $request)
    {
        if (!Session::has('oauthData')) {
            return redirect(route('login'));
        }

        $oauthUser = array_merge(Session::get('oauthData'), $request->only('name', 'email'));
        $userData = array_only($oauthUser, array_keys($request->rules()));
        $userData['register_source'] = $oauthUser['driver'];

        // 自 PHP 5.5 起，关键词 class 也可用于类名的解析。
        // 使用 ClassName::class 你可以获取一个字符串，包含了类 ClassName 的完全限定名称。
        // 这对使用了 命名空间 的类尤其有用。
        return app(\App\Phphub\Creators\UserCreator::class)->create($this, $userData);
    }

    public function logout()
    {
        Auth::logout();

        flash(lang('Operation succeeded.'), 'success');
        return redirect(route('home'));
    }

    public function userBanned()
    {
        if (Auth::check() && Auth::user()->is_banned == 'no') {
            return redirect(route('home'));
        }

        // force logout
        Auth::logout();

        return view('auth.userBanned');
    }

    /**
     * ----------------------------------------
     * Login Delegate
     * ----------------------------------------
     */
    // 数据库找不到用户, 执行新用户注册
    private function userNotFound($registerUserData, $driver)
    {
        if ($driver == 'github') {
            $oauthData['image_url'] = $registerUserData->user['avatar_url'];
            $oauthData['github_id'] = $registerUserData->user['id'];
            $oauthData['github_url'] = $registerUserData->user['url'];          // html_url
            $oauthData['github_name'] = $registerUserData->nickname;
            $oauthData['name'] = $registerUserData->user['name'];
            $oauthData['email'] = $registerUserData->user['email'];
        } else if ($driver == 'wechat') {
            $oauthData['image_url'] = $registerUserData->avatar;
            $oauthData['wechat_openid'] = $registerUserData->id;
            $oauthData['name'] = $registerUserData->nickname;
            $oauthData['email'] = $registerUserData->email;
            $oauthData['wechat_unionid'] = $registerUserData->user['unionid'];
        }

        $oauthData['driver'] = $driver;
        Session::put('oauthData', $oauthData);

        return redirect(route('signup'));
    }

    // 数据库中有，登录用户
    private function loginUser($user)
    {
        if ($user->is_banned == 'yes') {
            return $this->userIsBanned($user);
        }

        return $this->userFound($user);
    }

    private function userFound($user)
    {
        Auth::login($user, true);
        Session::forget('oauthData');

        flash(lang('Login Successfully.'), 'success');

        return redirect(route('users.edit', $user->id));
    }

    private function userIsBanned($user)
    {
        return redirect(route('user-banned'));
    }

    /**
     * ----------------------------------------
     * UserCreatorListener Delegate
     * ----------------------------------------
     */
    public function userValidationError($errors)
    {
        return redirect()->route('home');
    }

    public function userCreated($user)
    {
        Auth::login($user, true);
        Session::forget('oauthData');

        flash(lang('Congratulations and Welcome!'), 'success');

        // 返回首页
        return redirect(route('users.edit', Auth::user()->id));
    }
}
