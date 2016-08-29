<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotifyMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $type;
    protected $fromUser;
    protected $toUser;
    protected $body;
    protected $topic;
    protected $reply;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, User $fromUser, User $toUser, Topic $topic = null, Reply $reply = null, $body = null)
    {
        $this->type = $type;
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
        $this->topic = $topic;
        $this->reply = $reply;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app('App\Phphub\Handler\EmailHandler')->sendNotifyMail($this->type, $this->fromUser, $this->toUser, $this->topic, $this->reply, $this->body);
    }
}
