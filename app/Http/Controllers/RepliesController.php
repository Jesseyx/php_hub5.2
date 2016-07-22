<?php

namespace App\Http\Controllers;

use App\Phphub\Core\CreatorListener;
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
