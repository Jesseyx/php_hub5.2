<?php

namespace App\Phphub\Notification;

use App\Append;
use App\Notification;
use App\Reply;
use App\Topic;
use App\User;

class Notifier
{
    private $notifiedUsers = [];

    public function newReplyNotify(User $fromUser, Mention $mentionParser, Topic $topic, Reply $reply)
    {
        // Notify the author, 向作者发送提醒
        Notification::batchNotify(
            'new_reply',
            $fromUser,
            $this->removeDuplication([$topic->user]),
            $topic,
            $reply
        );

        // Notify attented users，向关注的人发送提醒
        Notification::batchNotify(
            'attention',
            $fromUser,
            $this->removeDuplication($topic->attentedBy),
            $topic,
            $reply
        );

        // Notify mentioned users, 想 @ 的用户发送提醒
        Notification::batchNotify(
            'at',
            $fromUser,
            $this->removeDuplication($mentionParser->users),
            $topic,
            $reply
        );
    }

    public function newAppendNotify(User $fromUser, Topic $topic, Append $append)
    {
        // 获取数据列值列表, 只获取 user 列
        $users = $topic->replies()->with('user')->get()->lists('user');

        // Notify commented user, 向评论的人发送提醒
        Notification::batchNotify(
            'comment_append',
            $fromUser,
            $this->removeDuplication($users),
            $topic,
            null,
            $append->content
        );

        // Notify attented users，向关注的人发送提醒
        Notification::batchNotify(
            'attention_append',
            $fromUser,
            $this->removeDuplication($topic->attentedBy),
            $topic,
            null,
            $append->content
        );
    }

    // in case of a user get a lot of the same notification
    private function removeDuplication($users)
    {
        $notYetNotifyUsers = [];

        foreach ($users as $user) {
            if (!in_array($user->id, $this->notifiedUsers)) {
                $notYetNotifyUsers[] = $user;
                $this->notifiedUsers[] = $user->id;
            }
        }

        return $notYetNotifyUsers;
    }
}
