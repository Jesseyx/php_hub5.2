<?php

namespace App\Phphub\Creators;

use App\Phphub\Core\CreatorListener;
use App\Phphub\Markdown\Markdown;
use App\Phphub\Notification\Mention;
use App\Phphub\Notification\Notifier;
use App\Reply;
use App\Topic;
use Auth;
use Carbon\Carbon;

class ReplyCreator
{
    protected $mentionParser;

    public function __construct(Mention $mentionParser)
    {
        $this->mentionParser = $mentionParser;
    }

    public function create(CreatorListener $observer, $data)
    {
        $data['user_id'] = Auth::id();
        // 处理 @ 消息，后期处理
         $data['body'] = $this->mentionParser->parse($data['body']);

        $markdown = new Markdown();

        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);

        $reply = Reply::create($data);

        if (!$reply) {
            return $observer->creatorFailed($reply->getErrors());
        }

        // Add the reply user
        $topic = Topic::find($data['topic_id']);
        $topic->last_reply_user_id = Auth::id();
        $topic->reply_count++;
        $topic->updated_at = Carbon::now()->toDateTimeString();
        $topic->save();

        Auth::user()->increment('reply_count', 1);

        app(Notifier::class)->newReplyNotify(Auth::user(), $this->mentionParser, $topic, $reply);

        return $observer->creatorSucceed($reply);
    }
}
