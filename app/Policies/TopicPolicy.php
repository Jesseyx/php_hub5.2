<?php

namespace App\Policies;

use App\Topic;
use App\User;
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
        return $user->may('manage_topics');
    }

    public function update(User $user, Topic $topic)
    {
        return $user->may('manage_topics') || $user->id == $topic->user_id;
    }
}
