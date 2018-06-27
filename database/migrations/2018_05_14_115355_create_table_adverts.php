<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdverts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('广告标题');
            $table->string('thumb')->comment('图片链接');
            $table->string('link')->nullable()->comment('跳转链接');
            $table->tinyInteger('sort')->default(0)->comment('排序');
            $table->integer('position_id')->comment('位置ID');
            $table->text('description')->nullable()->comment('广告描述');
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
        Schema::dropIfExists('adverts');
    }
}
