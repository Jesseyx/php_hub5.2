<?php

namespace App\Phphub\Presenters;

use App\Models\Role;
use Laracasts\Presenter\Presenter;
use Route;

class UserPresenter extends Presenter
{
    /**
     * Present a link to the user gravatar.
     */
    public function gravatar($size = 100)
    {
        if (config('phphub.url_static') && $this->avatar) {
            //Using Qiniu image processing service.
            $postfix = $size > 0 ? "?imageView2/1/w/{$size}/h/{$size}" : '';
            return cdn('uploads/avatars/'.$this->avatar) . $postfix;
        } else if ($this->avatar) {
            return config('phphub.url') . 'uploads/avatars/' . $this->avatar;
        }

        $github_id = $this->github_id;
        $domainNumber = rand(0, 3);

        return "https://avatars{$domainNumber}.githubusercontent.com/u/{$github_id}?v=2&s={$size}";
    }

    /*
     * 是否有徽章
     */
    public function hasBadge()
    {
        $relations = Role::relationArrayWithCache();
        // array_pluck 方法从数组中返回给定键对应的键值对列表
        $user_ids = array_pluck($relations, 'user_id');

        return in_array($this->id, $user_ids);
    }

    /*
     * 获取徽章名称
     */
    public function badgeName()
    {
        $relations = Role::relationArrayWithCache();
        // array_first 方法返回通过测试数组的第一个元素
        $relation = array_first($relations, function ($key, $value) {
            return $value->user_id == $this->id;
        });

        if (!$relation) {
            return;
        }

        $roles = Role::rolesArrayWithCache();

        $role = array_first($roles, function ($key, $value) use (&$relation) {
            return $value->id == $relation->role_id;
        });

        return $role->name;
    }

    public function userInfoNavActive($anchor)
    {
        return Route::currentRouteName() == $anchor ? 'active' : '';
    }

    public function followingUsersJson()
    {
        $users = \Auth::user()->followings()->lists('name');
        return \GuzzleHttp\json_encode($users);
    }
}
