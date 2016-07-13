<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();

        // insert 不会生成时间
        DB::table('categories')->insert([
            [
                'parent_id' => 0,
                'name' => '招聘',
                'slug' => 'zhao-pin',
                'parent_id' => 0,
                'description' => '这里有高质量的 PHPer, 记得认真填写你的需求, 薪资待遇是必须写的哦.',
                'weight' => 100,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],

            [
                'parent_id' => 0,
                'name' => '公告',
                'slug' => 'gong-gao',
                'parent_id' => 0,
                'description' =>null,
                'weight' => 97,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],

            [
                'parent_id' => 0,
                'name' => '问答',
                'slug' => 'wen-da',
                'parent_id' => 0,
                'description' =>null,
                'weight' => 99,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],

            [
                'parent_id' => 0,
                'name' => '分享',
                'slug' => 'fen-xiang',
                'parent_id' => 0,
                'description' =>null,
                'weight' => 98,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],
        ]);
    }
}
