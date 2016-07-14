<?php

namespace App\Http\Controllers\Auth;

use App\Phphub\Listeners\UserCreatorListener;
use App\User;
use App\Http\Controllers\Controller;
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
        dd('adasdasd');
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
     * ----------------------------------------
     * UserCreatorListener Delegate
     * ----------------------------------------
     */
    public function userValidationError($errors)
    {
        // TODO: Implement userValidationError() method.
    }

    public function userCreated($user)
    {
        // TODO: Implement userCreated() method.
    }
}
