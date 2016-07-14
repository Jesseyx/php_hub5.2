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
            $table->string('name')->nullable()->index();
            $table->string('real_name')->nullable();
            $table->string('email')->nullable()->index();
            // github avatar_url
            $table->string('image_url')->nullable();
            $table->string('avatar');
            $table->string('city')->nullable();
            $table->string('company')->nullable();
            $table->string('introduction')->nullable();
            $table->string('personal_website')->nullable();
            $table->string('twitter_account')->nullable();
            $table->integer('github_id')->index();
            $table->string('github_url');
            $table->string('github_name')->index();
            $table->integer('topic_count')->default(0)->index();
            $table->integer('reply_count')->default(0)->index();
            // 通知
            $table->integer('notification_count')->default(0);
            // 是否禁止
            $table->enum('is_banned', ['yes', 'no'])->default('no')->index();
            $table->string('login_token')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('login_qr');
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
