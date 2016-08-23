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
        DB::table('categories')->delete();

        // insert 不会生成时间
        DB::table('categories')->insert([
            [
                'parent_id' => 0,
                'name' => '招聘',
                'slug' => 'zhao-pin',
                'parent_id' => 0,
                'description' => 'PHPHub 是国内最大的 PHP & Laravel 社区，招聘、求职、外包等相关的话题，都可以在此主题下发布。<br>请遵照 <a href="https://phphub.org/topics/817">PHPHub 招聘贴发布规范</a>，不遵循规范，会被视为对用户的不尊重，管理员会做沉贴操作，沉贴后用户基本上看不到帖子。符合规范，我们会为话题加精并推荐到网站首页、手机端首页、精华帖周报邮件、<a href="http://weibo.com/phphub">微博官方账号</a>。',
                'weight' => 100,
                'post_count' => 0,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],

            [
                'parent_id' => 0,
                'name' => '公告',
                'slug' => 'gong-gao',
                'parent_id' => 0,
                'description' => '社区公告，小朋友不要做坏事哦。',
                'weight' => 97,
                'post_count' => 0,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],

            [
                'parent_id' => 0,
                'name' => '问答',
                'slug' => 'wen-da',
                'parent_id' => 0,
                'description' => '新手问答，请仔细阅读 <a href="https://phphub.org/topics/535">关于《提问的智慧》</a>，质量太低的问题，不遵循规范，会被视为对用户的不尊重，管理员会做沉贴操作，沉贴后用户基本上看不到帖子。排版清晰，信息完整的，我们会为话题加精，加精后的帖子将会被推荐到网站首页、手机端首页、精华帖周报邮件、<a href="http://weibo.com/phphub">微博官方账号</a>。',
                'weight' => 99,
                'post_count' => 0,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],

            [
                'parent_id' => 0,
                'name' => '分享',
                'slug' => 'fen-xiang',
                'parent_id' => 0,
                'description' => '分享创造，分享知识，分享灵感，分享创意，分享美好的东西。排版清晰，内容精良的话，我们会为话题加精，加精后的帖子将会被推荐到网站首页、手机端首页、精华帖周报邮件、<a href="http://weibo.com/phphub">微博官方账号</a>。',
                'weight' => 98,
                'post_count' => 0,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],

            [
                'parent_id' => 0,
                'name' => '教程',
                'slug' => 'tutorial',
                'parent_id' => 0,
                'description' => '教程文章请存放在此分类下，转载文章请注明「转载于」声明。',
                'weight' => 98,
                'post_count' => 0,
                'created_at' => '2016-07-13 20:00:00',
                'updated_at' => '2016-07-13 20:00:00',
            ],
        ]);
    }
}
