<?php

namespace App\Http\Controllers\Traits;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Socialite;

trait SocialiteHelper
{
    protected $oauthDrivers = ['github' => 'github', 'wechat' => 'weixinweb'];

    public function oauth(Request $request)
    {
        $driver = $request->input('driver');
        $driver = !isset($this->oauthDrivers[$driver]) ? 'github' : $this->oauthDrivers[$driver];

        if (Auth::check() && Auth::user()->register_source == $driver) {
            return redirect('/');
        }

        return Socialite::driver($driver)->redirect();
    }

    public function callback(Request $request)
    {
        // windows 系统会因为 ssl 证书报错，请参考下面的解决办法
        // https://laracasts.com/discuss/channels/laravel/how-to-solve-curl-error-60-ssl-certificate-in-laravel-5-while-facebook-authentication
        // lalitesh       If someone is still looking for a solution, there is an easy fix   ...
        // App::make 从容器中解析对象，已通过 composer.json psr-4 自动加载
        $driver = $request->input('driver');

        if (!isset($this->oauthDrivers[$driver]) || (Auth::check() && Auth::user()->register_source == $driver)) {
            return redirect()->intended('/');
        }

        $oauthUser = Socialite::driver($this->oauthDrivers[$driver])->user();

        $user = User::getByDriver($driver, $oauthUser->id);

        if (Auth::check()) {
            if ($user && $user->id !== Auth::id()) {
                // 已经登录但是不是同一个人
                flash(lang('Sorry, this socialite account has been registered.', ['driver' => lang($driver)]), 'error');
            } else {
                $this->bindSocialiteUser($oauthUser, $driver);
                flash(lang('Bind Successfully!', ['driver' => lang($driver)]), 'success');
            }

            return redirect(route('users.edit_social_binding', Auth::id()));
        } else {
            if ($user) {
                return $this->loginUser($user);
            }

            return $this->userNotFound($oauthUser, $driver);
        }
    }

    public function bindSocialiteUser($oauthUser, $driver)
    {
        $currentUser = Auth::user();

        if ($driver == 'github') {
            $currentUser->github_id = $oauthUser->id;
            $currentUser->github_url = $oauthUser->user['url'];
        } elseif ($driver == 'wechat') {
            $currentUser->wechat_openid = $oauthUser->id;
            $currentUser->wechat_unionid = $oauthUser->user['unionid'];
        }

        $currentUser->save();
    }
}
