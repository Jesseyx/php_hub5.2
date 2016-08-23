<?php

use App\Models\Role;
use App\Models\User;

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
        $users = factory(User::class, 49)->make()->each(function ($user, $i) {
            if ($i == 0) {
                $user->name = 'admin';
                $user->email = 'admin@estgroupe.com';
                $user->github_name = 'admin';
            }

            // 确保 id 唯一
            $user->github_id = $i + 1;
        });

        User::insert($users->toArray());

        $hall_of_fame = Role::addRole('HallOfFame', '名人堂');
        $users = User::all();
        foreach ($users as $key => $user) {
            $user->attachRole($hall_of_fame);
        }
    }
}
