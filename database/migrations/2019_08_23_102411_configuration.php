<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Configuration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuration', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('group_id')->default(0)->comment('组ID');
            $table->string('label')->comment('配置项名称');
            $table->string('key')->comment('配置项字段');
            $table->string('val')->nullable()->comment('配置项值');
            $table->string('type')->default('input')->comment('配置项类型，input输入框，radio单选，select下拉,image单图片');
            $table->text('content')->nullable()->comment('配置项类型的内容');
            $table->string('tips')->nullable()->comment('输入提示');
            $table->tinyInteger('sort')->default(10)->comment('排序');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `configuration` comment '配置项表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuration');
    }
}
