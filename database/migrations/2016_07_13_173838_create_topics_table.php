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
            $table->text('body_original')->nullable();                                  // 源
            $table->text('excerpt')->nullable();                                        // 摘录

            $table->integer('user_id')->unsigned()->default(0)->index();
            $table->integer('category_id')->unsigned()->default(0)->index();            // 所属大栏目
            $table->integer('last_reply_user_id')->unsigned()->default(0)->index();

            $table->integer('reply_count')->default(0)->index();
            $table->integer('view_count')->unsigned()->default(0)->index();
            $table->integer('vote_count')->default(0)->index();                         // 票数

            $table->enum('is_excellent', ['yes',  'no'])->default('no')->index();       // 加精
            $table->enum('is_blocked', ['yes',  'no'])->default('no')->index();         // 锁定
            $table->enum('is_tagged', ['yes',  'no'])->default('no')->index();          // 标记

            $table->integer('order')->default(0)->index();
            $table->string('source')->index();                                          // 来源跟踪：iOS，Android

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
