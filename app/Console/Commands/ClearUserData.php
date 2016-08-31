<?php

namespace App\Console\Commands;

use App\Models\ActiveUser;
use App\Models\Notification;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\Vote;
use Illuminate\Console\Command;

class ClearUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phphub:clear-user-data {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear user data { user_id : User ID }';

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
        $user_id = $this->argument('user_id');

        Topic::where('user_id', $user_id)->delete();
        Reply::where('user_id', $user_id)->delete();
        Notification::where('user_id', $user_id)->delete();
        Vote::where('user_id', $user_id)->delete();
        ActiveUser::where('user_id', $user_id)->delete();
    }
}
