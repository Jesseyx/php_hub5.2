<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('links')->delete();

        DB::table('links')->insert([
            [
                'id' => 1,
                'title' => 'Ruby China',
                'link' => 'https://ruby-china.org',
                'cover' => 'https://dn-phphub.qbox.me/assets/images/friends/ruby-china.png',
                'created_at' => '2016-08-18 16:10:10',
                'updated_at' => '2016-08-18 16:10:20',
            ],

            [
                'id' => 2,
                'title' => 'Golang 中国',
                'link' => 'http://golangtc.com/',
                'cover' => 'https://dn-phphub.qbox.me/assets/images/friends/golangcn.png',
                'created_at' => '2016-08-18 16:11:10',
                'updated_at' => '2016-08-18 16:11:20',
            ],

            [
                'id' => 3,
                'title' => 'CNode：Node.js 中文社区',
                'link' => 'http://cnodejs.org/',
                'cover' => 'https://dn-phphub.qbox.me/assets/images/friends/cnodejs.png',
                'created_at' => '2016-08-18 16:12:10',
                'updated_at' => '2016-08-18 16:12:20',
            ],

            [
                'id' => 4,
                'title' => 'ElixirChina (ElixirCN) ',
                'link' => 'http://elixir-cn.com/',
                'cover' => 'https://dn-phphub.qbox.me/f65fb5a10d3392a1db841c85716dd8f6.png',
                'created_at' => '2016-08-18 16:13:10',
                'updated_at' => '2016-08-18 16:13:20',
            ],

            [
                'id' => 5,
                'title' => 'Ionic China',
                'link' => 'http://ionichina.com/',
                'cover' => 'https://dn-phphub.qbox.me/assets/images/friends/ionic.png',
                'created_at' => '2016-08-18 16:14:10',
                'updated_at' => '2016-08-18 16:14:20',
            ],

            [
                'id' => 6,
                'title' => 'Tester Home',
                'link' => 'https://testerhome.com',
                'cover' => 'https://dn-phphub.qbox.me/testerhome-logo.png',
                'created_at' => '2016-08-18 16:15:10',
                'updated_at' => '2016-08-18 16:15:20',
            ],

            [
                'id' => 7,
                'title' => 'Laravel So',
                'link' => 'http://laravel.so/',
                'cover' => 'http://laravel.so/img/site-logo.png',
                'created_at' => '2016-08-18 16:16:10',
                'updated_at' => '2016-08-18 16:16:20',
            ],
        ]);
    }
}
