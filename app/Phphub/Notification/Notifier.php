<?php

namespace App\Phphub\Notification;

use App\Models\Append;
use App\Models\Notification;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;

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
            $this->removeDuplication($topic->votedBy()),
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
            'vote_append',
            $fromUser,
            $this->removeDuplication($topic->votedBy()),
            $topic,
            null,
            $append->content
        );
    }

    public function newFollowNotify(User $fromUser, User $toUser)
    {
        Notification::notify(
            'follow',
            $fromUser,
            $toUser,
            null,
            null,
            null);
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
