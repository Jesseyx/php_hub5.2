<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index()->comment('名称');
            $table->string('slug', 60)->unique()->comment('缩略名');
            $table->integer('parent_id')->default(0)->comment('父级 id');
            $table->string('description')->nullable()->comment('描述');
            $table->tinyInteger('weight')->default(0)->comment('权重');
            $table->integer('post_count')->default(0)->comment('帖子数');
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
        Schema::drop('categories');
    }
}
