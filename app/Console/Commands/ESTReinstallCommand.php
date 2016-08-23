<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ESTReinstallCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'est:reinstall {--force : enforce}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database, reset RABC. Only use in local environment';

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
        $this->productionCheckHint('Reset database and reset RABC');

        // fixing db:seed class not found
        $this->execShellWithPrettyPrint('composer dump');

        $this->execShellWithPrettyPrint('php artisan est:dbreset --force');
        $this->execShellWithPrettyPrint('php artisan est:init-rbac');

        $this->printBenchInfo();
    }
}
