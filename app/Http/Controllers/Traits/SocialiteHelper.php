<?php

namespace App\Http\Controllers\Traits;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Socialite;

trait SocialiteHelper
{
    protected $oauthDrivers = ['github' => 'github', 'wechat' => 'weixin'];

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
        $driver = $request->input('driver');

        if (!isset($this->oauthDrivers[$driver]) || (Auth::check() && Auth::user()->register_source == $driver)) {
            return redirect()->intended('/');
        }

        $oauthUser = Socialite::driver($this->oauthDrivers[$driver])->user();

        $user = User::getByGithubId();
    }
}
