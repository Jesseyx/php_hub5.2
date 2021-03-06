<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function recommend(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }

    public function pin(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }

    public function sink(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }

    public function delete(User $user, Topic $topic)
    {
        // 不支持用户删帖
        // return $user->id === $topic->user_id;
        return $user->may('manage_topics');
    }

    public function update(User $user, Topic $topic)
    {
        return $user->may('manage_topics') || $user->id == $topic->user_id;
    }

    public function append(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }

    public function wiki(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }
}
