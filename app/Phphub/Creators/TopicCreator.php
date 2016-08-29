<?php
namespace App\Phphub\Creators;

use App\Models\Topic;
use App\Phphub\Core\CreatorListener;
use App\Phphub\Markdown\Markdown;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;

class TopicCreator
{
    public function create(CreatorListener $observer, $data)
    {
        // 检查是否重复发布
        if ($this->checkIsDuplicate($data)) {
            $errorMessages = new MessageBag;
            $errorMessages->add('duplicated', '请不要发布重复内容。');
            return $observer->creatorFailed($errorMessages);
        }

        $data['user_id'] = Auth::id();
        $data['created_at'] = Carbon::now()->toDateTimeString();
        $data['updated_at'] = Carbon::now()->toDateTimeString();

        $markdown = new Markdown;

        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        // 摘录
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        $data['source'] = get_platform();

        $topic = Topic::create($data);

        if (!$topic) {
            return $observer->creatorFailed($topic->getErrors());
        }

        Auth::user()->increment('topic_count', 1);

        return $observer->creatorSucceed($topic);
    }

    protected function checkIsDuplicate($data)
    {
        $last_topic = Topic::where('user_id', Auth::id())
                            ->orderBy('id', 'desc')
                            ->first();
        return count($last_topic) && strcmp($last_topic->title, $data['title']) === 0;
    }
}
