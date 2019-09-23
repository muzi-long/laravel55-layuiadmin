<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Articles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id')->default(0)->comment('分类id');
            $table->string('title',200)->comment('标题');
            $table->string('keywords',200)->nullable()->comment('关键词');
            $table->text('description')->nullable()->comment('描述');
            $table->text('content')->comment('内容');
            $table->integer('click')->default(0)->comment('点击量');
            $table->string('thumb',200)->nullable()->comment('缩略图');
            $table->string('link')->nullable()->comment('外链');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `articles` comment '资讯表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
