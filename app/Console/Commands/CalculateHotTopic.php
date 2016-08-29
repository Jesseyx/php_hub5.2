<?php

namespace App\Console\Commands;

use App\Models\HotTopic;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\Vote;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class CalculateHotTopic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phphub:calculate-hot-topic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate hot topic';

    const PASS_DAYS = 7;
    const VOTE_TOPIC_WEIGHT = 5;
    const REPLY_TOPIC_WEIGHT = 3;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        HotTopic::query()->delete();

        $this->calculateTopics();
    }

    protected function calculateTopics()
    {
        $topics = Topic::where('created_at', '>=', Carbon::now()->subDays(self::PASS_DAYS))->get();
        foreach ($topics as $topic) {
            $data = [];
            $data['topic_id'] = $topic->id;
            $data['reply_count'] = Reply::where('topic_id', '=', $topic->id)->count();
            $data['vote_count'] = Vote::where('votable_type', 'App\Models\Topic')
                                        ->where('votable_id', $topic->id)
                                        ->where('is', 'upvote')
                                        ->count();

            $data['weight'] = $data['vote_count'] * self::VOTE_TOPIC_WEIGHT
                            + $data['reply_count'] * self::REPLY_TOPIC_WEIGHT;

            HotTopic::updateOrCreate(['topic_id' => $topic->id], $data);
        }
    }
}
