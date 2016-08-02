<?php

namespace App\Phphub\Vote;

use App\Topic;
use Auth;

class Voter
{
    public function topicUpVote(Topic $topic)
    {
        if ($topic->votes()->byWhom(Auth::id())->withType('upvote')->count()) {
            // click twice for remove upvote
            $topic->votes()->byWhom(Auth::id())->withType('upvote')->delete();
            $topic->decrement('vote_count', 1);
        } else if ($topic->votes()->byWhom(Auth::id())->withType('downvote')->count()) {
            // user already clicked downvote once
            $topic->votes()->byWhom(Auth::id())->withType('downvote')->delete();
            $topic->votes()->create(['user_id' => Auth::id(), 'is' => 'upvote']);
            $topic->increment('vote_count', 2);
        } else {
            // first time click
            $topic->votes()->create(['user_id' => Auth::id(), 'is' => 'upvote']);
            $topic->increment('vote_count', 1);
        }
    }

    public function topicDownVote(Topic $topic)
    {
        if ($topic->votes()->byWhom(Auth::id())->withType('downvote')->count()) {
            // click second time for remove downvote
            $topic->votes()->byWhom(Auth::id())->withType('downvote')->delete();
            $topic->increment('vote_count', 1);
        } else if ($topic->votes()->byWhom(Auth::id())->withType('upvote')->count()) {
            // user already clicked upvote once
            $topic->votes()->byWhom(Auth::id())->withType('upvote')->delete();
            $topic->votes()->create(['user_id' => Auth::id(), 'is' => 'downvote']);
            $topic->decrement('vote_count', 2);
        } else {
            // click first time
            $topic->votes()->create(['user_id' => Auth::id(), 'is' => 'downvote']);
            $topic->decrement('vote_count', 1);
        }
    }
}
