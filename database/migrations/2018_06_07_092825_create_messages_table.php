<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('消息标题');
            $table->text('content')->comment('消息内容')->nullable();
            $table->integer('read')->comment('1-未读，2-已读')->default(1);
            $table->uuid('send_uuid')->default(1)->comment('消息发送者UUID，1表示系统发送');
            $table->uuid('accept_uuid')->comment('消息接收者UUID');
             $table->integer('flag')->comment('消息对应关系：12-系统推送给后台，13-系统推送给前台,22-后台推送给后台，23-后台推送给前台，32-前台推送给后台，33-前台推送给前台，');
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
        Schema::dropIfExists('messages');
    }
}
