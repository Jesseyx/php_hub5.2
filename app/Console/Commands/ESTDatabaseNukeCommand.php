<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ESTDatabaseNukeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'est:dbnuke {--force : enforce}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all tables';

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
        $this->productionCheckHint();

        $colName = 'Tables_in_' . env('DB_DATABASE');

        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $dropList[] = $table->$colName;
            $this->info('Will delete table - ' . $table->$colName);
        }

        if (!isset($dropList)) {
            $this->error('No table');
            return;
        }
        // Table list
        $dropList = implode(', ', $dropList);

        DB::beginTransaction();
        //turn off referential integrity
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement("DROP TABLE $dropList");
        //turn referential integrity back on
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();

        // PHP_EOL 换行符，保证各个系统的兼容性
        $this->comment('All the tables have been deleted' . PHP_EOL);
    }
}
