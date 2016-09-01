<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phphub\Handler\EmailHandler;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReplyNotifyMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    protected $reply;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        EmailHandler::sendReplyNotifyMail($this->reply);
    }
}
