<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->text('body');
            $table->tinyInteger('user_id')->unsigned()->default(0)->index();
            // 所属大栏目
            $table->integer('category_id')->unsigned()->default(0)->index();
            $table->integer('reply_count')->default(0)->index();
            $table->integer('view_count')->unsigned()->default(0)->index();
            $table->integer('favorite_count')->default(0)->index();
            // 票数
            $table->integer('vote_count')->default(0)->index();
            $table->integer('last_reply_user_id')->unsigned()->default(0)->index();
            $table->integer('order')->default(0)->index();
            // 是否杰出的
            $table->enum('is_excellent', ['yes',  'no'])->default('no')->index();
            // 是否锁定
            $table->enum('is_blocked', ['yes',  'no'])->default('no')->index();

            // 源
            $table->text('body_original')->nullable();
            // 摘录
            $table->text('excerpt')->nullable();
            // 是否标记
            $table->enum('is_tagged', ['yes',  'no'])->default('no')->index();

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
        Schema::drop('topics');
    }
}
