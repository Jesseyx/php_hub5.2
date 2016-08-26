<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->nullable()->index();
            // 个人信息
            $table->string('name')->nullable()->index();
            $table->string('real_name')->nullable();
            $table->string('image_url')->nullable();                            // 站外 avatar_url
            $table->string('avatar');
            $table->string('city')->nullable();
            $table->string('company')->nullable();
            $table->string('introduction')->nullable();
            $table->string('personal_website')->nullable();
            $table->string('twitter_account')->nullable();
            $table->string('linkedin')->nullable();                            // LinkedIn 领英主页
            $table->string('certification')->nullable();                       // 证明认证证书
            // 个人统计
            $table->integer('topic_count')->default(0)->index();
            $table->integer('reply_count')->default(0)->index();
            $table->integer('follower_count')->default(0)->index();
            $table->integer('notification_count')->default(0);                  // 通知数量
            // ids
            $table->integer('github_id')->nullable()->index();
            $table->string('github_url')->nullable();
            $table->string('github_name')->nullable()->index();

            $table->string('wechat_openid')->nullable()->index();
            $table->string('wechat_unionid')->nullable()->index();

            $table->string('weibo_name')->nullable();
            $table->string('weibo_link')->nullable();
            // 状态控制
            $table->enum('is_banned', ['yes', 'no'])->default('no')->index();   // 是否禁止
            $table->boolean('verified')->default(false)->index();               // 是否认证, for laravel-user-verification
            // token
            $table->string('login_token')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('verification_token')->nullable();                   // for laravel-user-verification
            // qr 信息
            $table->string('login_qr')->nullable();
            $table->string('payment_qrcode')->nullable();
            $table->string('wechat_qrcode')->nullable();
            // 邮件开关
            $table->enum('email_notify_enabled', ['yes',  'no'])->default('yes')->index();
            // 注册及登录记录
            $table->string('register_source')->index();
            $table->timestamp('last_actived_at')->nullable();                   // 活跃于

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
