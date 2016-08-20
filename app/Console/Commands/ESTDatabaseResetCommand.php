<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ESTDatabaseResetCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'est:dbreset {--force : enforce}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete all tables（use est:dbnuke ）, and run the 'migrate' and 'db:seed' commands";

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
        $this->productionCheckHint("Will delete all tables, and run the 'migrate' and 'db:seed' commands");

        $this->call('est:dbnuke', [
            '--force' => 'yes'
        ]);

        $this->call('migrate', [
            '--seed'  => 'yes',
            '--force' => 'yes',
        ]);
    }
}
