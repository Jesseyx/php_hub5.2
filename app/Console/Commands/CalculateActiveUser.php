<?php

namespace App\Console\Commands;

use App\Models\ActiveUser;
use App\Models\Reply;
use App\Models\Topic;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class CalculateActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phphub:calculate-active-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate active user';

    const PASS_DAYS = 7;
    const POST_TOPIC_WEIGHT = 4;
    const POST_REPLY_WEIGHT = 1;

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
        ActiveUser::query()->delete();

        $this->calculateTopicUsers();
        $this->calculateReplyUsers();
        $this->calculateWeight();
    }

    protected function calculateTopicUsers()
    {
        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
                                    ->where('created_at', '>=', Carbon::now()->subDays(self::PASS_DAYS))
                                    ->groupBy('user_id')
                                    ->get();

        foreach($topic_users as $value) {
            $data = [];
            $data['user_id'] = $value->user_id;
            $data['topic_count'] = $value->topic_count;

            // Create or update a record matching the attributes, and fill it with values.
            ActiveUser::updateOrCreate(['user_id' => $value->user_id], $data);
        }
    }

    protected function calculateReplyUsers()
    {
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays(self::PASS_DAYS))
            ->groupBy('user_id')
            ->get();

        foreach($reply_users as $value) {
            $data = [];
            $data['user_id'] = $value->user_id;
            $data['reply_count'] = $value->reply_count;

            // Create or update a record matching the attributes, and fill it with values.
            ActiveUser::updateOrCreate(['user_id' => $value->user_id], $data);
        }
    }

    protected function calculateWeight()
    {
        $active_users = ActiveUser::all();

        foreach ($active_users as $active_user) {
            $active_user->weight = $active_user->topic_count * self::POST_TOPIC_WEIGHT
                + $active_user->reply_count * self::POST_REPLY_WEIGHT;
            $active_user->save();
        }
    }
}
