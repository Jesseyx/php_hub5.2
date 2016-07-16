<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\StoreUserRequest;
use App\Phphub\Listeners\UserCreatorListener;
use App\User;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Socialite;
use Session;

class AuthController extends Controller implements UserCreatorListener
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /*
     * Login
     */
    public function login(Request $request)
    {
        if ($request->has('code')) {
            // 返回了
            // windows 系统会因为 ssl 证书报错，请参考下面的解决办法
            // https://laracasts.com/discuss/channels/laravel/how-to-solve-curl-error-60-ssl-certificate-in-laravel-5-while-facebook-authentication
            // lalitesh       If someone is still looking for a solution, there is an easy fix   ...
            // App::make 从容器中解析对象，已通过 composer.json psr-4 自动加载

            $githubUser = Socialite::driver('github')->user();
            $user = User::getByGithubId($githubUser->id);

            if ($user) {
                return $this->loginUser($user);
            }

            return $this->userNotFound($githubUser);
        }

        // 将用户重定向到 GitHub 认证页面
        return Socialite::driver('github')->redirect();
    }

    // 数据库中有，登录用户
    private function loginUser($user)
    {
        if ($user->is_banned == 'yes') {
            return $this->userIsBanned($user);
        }

        return $this->userFound($user);
    }

    /**
     * ----------------------------------------
     * GithubAuthenticatorListener Delegate
     * ----------------------------------------
     */

    // 数据库找不到用户, 执行新用户注册
    private function userNotFound($githubData)
    {
        $githubUserData = $githubData->user;
        $githubUserData['image_url'] = $githubData->user['avatar_url'];
        $githubUserData['github_id'] = $githubData->user['id'];
        $githubUserData['github_url'] = $githubData->user['url'];
        $githubUserData['github_name'] = $githubData->nickname;

        Session::put('githubUserData', $githubUserData);

        return redirect(route('signup'));
    }

    private function userIsBanned($user)
    {
        return redirect(route('user-banned'));
    }

    private function userFound($user)
    {
        Auth::login($user, true);
        Session::forget('githubUserData');

        return redirect()->intended();
    }

    /**
     * Shows a user what their new account will look like.
     */
    public function create()
    {
        if (! Session::has('githubUserData')) {
            return redirect(route('login'));
        }

        $githubUser = array_merge(Session::get('githubUserData'), Session::get('_old_input', []));
        return view('auth.signupconfirm', compact('githubUser'));
    }

    /**
     * Actually creates the new user account
     */
    public function store(StoreUserRequest $request)
    {
        if (! Session::has('githubUserData')) {
            return redirect(route('login'));
        }

        $githubUser = array_merge(Session::get('githubUserData'), $request->only('github_id', 'name', 'github_name', 'email'));
        $githubUser = array_only($githubUser, array_keys($request->rules()));

        // 自 PHP 5.5 起，关键词 class 也可用于类名的解析。
        // 使用 ClassName::class 你可以获取一个字符串，包含了类 ClassName 的完全限定名称。
        // 这对使用了 命名空间 的类尤其有用。
        return app(\App\Phphub\Creators\UserCreator::class)->create($this, $githubUser);
    }

    /*
     * Logout
     */
    public function logout()
    {
        Auth::logout();

        return redirect(route('home'));
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
        Session::forget('githubUserData');

        // 返回首页
        return redirect()->intended();
    }
}
