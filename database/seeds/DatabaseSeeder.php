<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        insanity_check();

        // 注意顺序
        $this->call(CategoriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TopicsTableSeeder::class);
        $this->call(RepliesTableSeeder::class);
        $this->call(BannersTableSeeder::class);
        $this->call(LinksTableSeeder::class);
        $this->call(FollowersTableSeeder::class);
        $this->call(SitesTableSeeder::class);
    }
}
