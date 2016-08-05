<?php

namespace App\Http\Controllers;

use App\Favorite;
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
        }

        return response(['status' => 200, 'message' => $message]);
    }
}
