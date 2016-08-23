<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('position')->index();
            $table->string('title')->index();
            $table->string('link')->nullable();
            $table->enum('target', ['_blank', '_self'])->default('_blank')->index();
            $table->string('image_url');
            $table->text('description')->nullable();

            $table->integer('order')->unsigned()->default(0)->index();

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
        Schema::drop('banners');
    }
}
