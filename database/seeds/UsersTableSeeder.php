<?php

use App\User;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        $users = factory(User::class, 50)->make()->each(function ($user, $i) {
            if ($i == 0) {
                $user->name = 'admin';
                $user->email = 'admin@estgroupe.com';
            }

            // 确保 id 唯一
            $user->github_id = $i + 1;
        });

        User::insert($users->toArray());
    }
}
