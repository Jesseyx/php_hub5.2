<?php

use App\Models\Site;
use Illuminate\Database\Seeder;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = factory(Site::class)->times(300)->make();
        Site::insert($sites->toArray());
    }
}
