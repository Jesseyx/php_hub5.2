<?php

namespace App\Console\Commands;

use Cache;
use Illuminate\Console\Command;

class SyncUserActivedTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phphub:sync-user-actived-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user actived time';

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
        // 获取并删除
        $data = Cache::pull(config('phphub.actived_time_for_update'));

        if (!$data) {
            $this->error('Error: No Data!');
            return false;
        }

        foreach ($data as $user_id => $last_actived_at) {
            User::query()->where('id', $user_id)
                ->update(['last_actived_at' => $last_actived_at]);
        }

        $this->info('Done!');
    }
}
