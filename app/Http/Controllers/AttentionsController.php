<?php

namespace App\Http\Controllers;

use App\Attention;
use App\Notification;
use App\Topic;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;

class AttentionsController extends Controller
{
    public function createOrDelete($id)
    {
        $topic = Topic::find($id);

        if (Attention::isUserAttentedTopic(Auth::user(), $topic)) {
            $message = lang('Successfully remove attention.');
            // 移除多对多关系
            Auth::user()->attentTopics()->detach($topic->id);
        } else {
            $message = lang('Successfully_attention');
            Auth::user()->attentTopics()->attach($topic->id);

            // 发送提醒
            Notification::notify('topic_attent', Auth::user(), $topic->user, $topic);
        }

        return response(['status' => 200, 'message' => $message]);
    }
}
