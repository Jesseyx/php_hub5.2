<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Notification;
use App\Topic;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;

class FavoritesController extends Controller
{
    public function createOrDelete($id)
    {
        $topic = Topic::find($id);

        if (Favorite::isUserFavoritedTopic(Auth::user(), $topic)) {
            $message = lang('Successfully remove favorite.');
            // 移除多对多关系
            Auth::user()->favoriteTopics()->detach($topic->id);
        } else {
            $message = lang('Successfully_favorite');
            Auth::user()->favoriteTopics()->attach($topic->id);

            // 发送提醒
            Notification::notify('topic_favorite', Auth::user(), $topic->user, $topic);
        }

        return response(['status' => 200, 'message' => $message]);
    }
}
