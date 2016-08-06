<?php

namespace App\Http\Controllers;

use App\Phphub\Core\CreatorListener;
use App\Reply;
use Illuminate\Http\Request;

use App\Http\Requests;

class RepliesController extends Controller implements CreatorListener
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Requests\StoreReplyRequest $request)
    {
        return app('App\Phphub\Creators\replyCreator')->create($this, $request->except('_token'));
    }

    public function vote($id)
    {
        $reply = Reply::find($id);
        $type = app('App\Phphub\Vote\Voter')->replyUpVote($reply);

        return response([
            'status' => 200,
            'message' => lang('Operation succeeded.'),
            'type' => $type['action_type'],
        ]);
    }

    public function destroy($id)
    {
        $reply = Reply::findOrFail($id);
        $this->authorize('delete', $reply);
        $reply->delete();

        $reply->topic->decrement('reply_count', 1);

        $reply->topic->generateLastReplyUserInfo($reply->user_id);

        return response([
            'status' => 200,
            'message' => lang('Operation succeeded.'),
        ]);
    }

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */
    public function creatorFailed($errors)
    {
        return response([
            'status'  => 500,
            'message' => lang('Operation failed!'),
        ]);
    }

    public function creatorSucceed($reply)
    {
        $reply->user->image_url = $reply->user->present()->gravatar;

        return response([
            'status' => 200,
            'message' => lang('Operation succeeded!'),
            'reply' => $reply,
            'manage_topics' => 'no',
        ]);
    }
}
