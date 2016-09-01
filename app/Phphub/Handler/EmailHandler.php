<?php

namespace App\Phphub\Handler;

use App\Models\NotificationMailLog;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Mail\Message;
use Jrean\UserVerification\Facades\UserVerification;
use Mail;
use Naux\Mail\SendCloudTemplate;

class EmailHandler
{
    protected $methodMap = [
        'at'                   => 'sendAtNotifyMail',                   // 评论时发送给 @ 的用户
        'comment_append'       => 'sendCommentAppendNotifyMail',        // 添加附言时发送给评论的用户
        'vote_append'          => 'sendVoteAppendNotifyMail',           // 添加附言时发送给点赞的用户
        'new_reply'            => 'sendNewReplyNotifyMail',             // 有新评论时发送给作者
        'attention'            => 'sendAttentionNotifyMail',            // 有新评论时发送给点赞的用户
        'topic_upvote'         => 'sendTopicUpvoteNotifyMail',          // 用户点赞时发送给作者
        'reply_upvote'         => 'sendReplyUpvoteNotifyMail',          // 评论点赞时
        'topic_mark_excellent' => 'sendTopicMarkExcellentNotifyMail',   // 主题被加精
        'follow'               => 'sendFollowNotifyMail',               // 跟随某用户
        'topic_attent'         => 'sendTopicAttentNotifyMail',
    ];

    protected $type;
    protected $fromUser;
    protected $toUser;
    protected $topic;
    protected $reply;
    protected $body;

    public function sendActivateMail(User $user)
    {
        UserVerification::generate($user);

        $token = $user->verification_token;
        // 第一个参数是包含邮件信息的视图名称
        // 第二个参数是你想要传递到该视图的数组数据
        // 第三个参数是接收消息实例的闭包回调——允许你自定义收件人、主题以及邮件其他方面的信息
        Mail::send('emails.fake', [], function (Message $message) use ($user, $token) {
            $message->subject(lang('Please verify your email address'));

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('template_active', [
                'name' => $user->name,
                'url'  => url('verification', $token) . '?email=' . urlencode($user->email),
            ]));

            $message->to($user->email);
        });
    }

    public function sendNotifyMail($type, User $fromUser, User $toUser, Topic $topic = null, Reply $reply = null, $body = null)
    {
        if (!isset($this->methodMap[$type])
            || $toUser->email_notify_enabled != 'yes'
            || $toUser->id == $fromUser->id
            || !$toUser->email || $toUser->verified != 1
        ) {
            return false;
        }

        $this->type = $type;
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
        $this->topic = $topic;
        $this->reply = $reply;
        $this->body = $body;

        $method = $this->methodMap[$type];
        $this->$method();
    }

    protected function sendCommentAppendNotifyMail()
    {
        if (!$this->body || !$this->topic) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('你留言的话题有新附言');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => '',
                'action'  => ' 你留言的话题: <a href="' . url(route('topics.show', $this->topic->id)) . '" target="_blank">' . $this->topic->title . '</a> 有新附言 <br /><br />附言内容如下：<br />',
                'content' => $this->body,
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog($this->body);
        });
    }

    protected function sendVoteAppendNotifyMail()
    {
        if (!$this->body || !$this->topic) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('你关注的话题有新附言');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => '',
                'action'  => ' 你关注的话题: <a href="' . url(route('topics.show', $this->topic->id)) . '" target="_blank">' . $this->topic->title . '</a> 有新附言 <br /><br />附言内容如下：<br />',
                'content' => $this->body,
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog($this->body);
        });
    }

    protected function sendNewReplyNotifyMail()
    {
        if (!$this->reply) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject(lang('Your topic have new reply'));

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => '<a href="' . url(route('users.show', $this->fromUser->id)) . '" target="_blank">' . $this->fromUser->name . '</a>',
                'action'  => ' 回复了你的主题: <a href="' . url(route('topics.show', $this->reply->topic_id)) . '" target="_blank">' . $this->reply->topic->title . '</a>  <br /><br />内容如下：<br />',
                'content' => $this->reply->body,
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog($this->reply->body);
        });
    }

    protected function sendAttentionNotifyMail()
    {
        if (!$this->reply) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户回复了你关注的主题');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => '<a href="' . url(route('users.show', $this->fromUser->id)) . '" target="_blank">' . $this->fromUser->name . '</a>',
                'action'  => ' 回复了你关注的主题: <a href="' . url(route('topics.show', $this->reply->topic_id)) . '" target="_blank">' . $this->reply->topic->title . '</a>  <br /><br />回复内容如下：<br />',
                'content' => $this->reply->body,
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog($this->reply->body);
        });
    }

    protected function sendAtNotifyMail()
    {
        if (!$this->reply) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户在主题中提及你');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => '<a href="' . url(route('users.show', $this->fromUser->id)) . '" target="_blank">' . $this->fromUser->name . '</a>',
                'action'  => ' 在主题: <a href="' . url(route('topics.show', $this->reply->topic_id)) . '" target="_blank">' . $this->reply->topic->title . '</a> 中提及了你 <br /><br />内容如下：<br />',
                'content' => $this->reply->body,
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog($this->reply->body);
        });
    }

    protected function sendTopicUpvoteNotifyMail()
    {
        if (!$this->topic) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户赞了你的主题');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => '<a href="' . url(route('users.show', $this->fromUser->id)) . '" target="_blank">' . $this->fromUser->name . '</a>',
                'action'  => ' 赞了你的主题: <a href="' . url(route('topics.show', $this->topic->id)) . '" target="_blank">' . $this->topic->title . '</a>',
                'content' => '',
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog($this->body);
        });
    }

    protected function sendReplyUpvoteNotifyMail()
    {
        if (!$this->reply) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户赞了你的回复');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => '<a href="' . url(route('users.show', $this->fromUser->id)) . '" target="_blank">' . $this->fromUser->name . '</a>',
                'action'  => ' 赞了你的回复: <a href="' . url(route('topics.show', $this->reply->topic_id)) . '" target="_blank">' . $this->reply->topic->title . '</a> <br /><br />你的回复内容如下：<br />',
                'content' => $this->reply->body,
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog($this->reply->body);
        });
    }

    protected function sendTopicMarkExcellentNotifyMail()
    {
        if (!$this->topic) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('管理员推荐了你的主题');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => '<a href="' . url(route('users.show', $this->fromUser->id)) . '" target="_blank">' . $this->fromUser->name . '</a>',
                'action'  => ' 推荐了你的主题: <a href="' . url(route('topics.show', $this->topic->id)) . '" target="_blank">' . $this->topic->title . '</a>',
                'content' => '',
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog();
        });
    }

    protected function sendFollowNotifyMail()
    {
        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户关注了你');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'     => '<a href="' . url(route('users.show', $this->fromUser->id)) . '" target="_blank">' . $this->fromUser->name . '</a>',
                'action'   => ' 关注了你',
                'content'  => '',
            ]));

            $message->to($this->toUser->email);
            $this->generateMailLog('');
        });
    }

    protected function generateMailLog($body = '')
    {
        $data = [];
        $data['from_user_id'] = $this->fromUser->id;
        $data['user_id'] = $this->toUser->id;
        $data['type'] = $this->type;
        $data['body'] = $body;
        $data['reply_id'] = $this->reply ? $this->reply->id : 0;
        $data['topic_id'] = $this->topic ? $this->topic->id : 0;

        NotificationMailLog::create($data);
    }
}
