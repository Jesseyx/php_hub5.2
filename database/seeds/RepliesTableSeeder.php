<?php

use App\User;
use App\Topic;
use App\Reply;

use Illuminate\Database\Seeder;

class RepliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('replies')->truncate();

        $users = User::lists('id')->toArray();
        $topics = Topic::lists('id')->toArray();

        $faker = app(Faker\Generator::class);

        // 注意 make 和 create 的区别，create 会保存到数据库, 他们返回的都是集合
        // create 返回的是和数据库关联的模型，make 返回的不是
        // 在表不存在的情况下 make 可以成功，create 不可以
        $replies = factory(Reply::class)->times(rand(300, 500))->make()->each(function ($reply) use ($faker, $users, $topics) {
            $reply->user_id = $faker->randomElement($users);
            $reply->topic_id = $faker->randomElement($topics);
        });

        Reply::insert($replies->toArray());
    }
}
