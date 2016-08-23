<?php

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('topics')->truncate();

        // 避免在 ModelFactory 中获取 id 列表
        $users = User::lists('id')->toArray();
        $categories = Category::lists('id')->toArray();

        $faker = app(Faker\Generator::class);

        $topics = factory(Topic::class)->times(rand(100, 200))->make()->each(function ($topic) use ($faker, $users, $categories) {
            $topic->user_id = $faker->randomElement($users);
            $topic->category_id = $faker->randomElement($categories);
            $topic->is_excellent = rand(0, 1) ? 'yes' : 'no';
        });
        Topic::insert($topics->toArray());

        $admin_topics = factory(Topic::class)->times(rand(1, 100))->make()->each(function ($topic) use ($faker,  $categories) {
            $topic->user_id = 1;
            $topic->category_id = $faker->randomElement($categories);
        });
        Topic::insert($admin_topics->toArray());
    }
}
