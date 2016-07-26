<?php
namespace App\Phphub\Creators;

use App\Phphub\Core\CreatorListener;
use App\Phphub\Markdown\Markdown;
use App\Topic;
use Auth;
use Carbon\Carbon;

class TopicCreator
{
    public function create(CreatorListener $observer, $data)
    {
        $data['user_id'] = Auth::id();
        $data['create_at'] = Carbon::now()->toDateTimeString();
        $data['updated_at'] = Carbon::now()->toDateTimeString();

        $markdown = new Markdown;

        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        // 摘录
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        $topic = Topic::create($data);

        if (!$topic) {
            return $observer->creatorFailed($topic->getErrors());
        }

        Auth::user()->increment('topic_count', 1);

        return $observer->creatorSucceed($topic);
    }
}
