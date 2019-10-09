<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('后台用户ID');
            $table->string('username')->comment('后台用户登录账号');
            $table->string('realname')->comment('后台用户真实姓名');
            $table->string('method')->comment('请求方式');
            $table->string('uri')->comment('请求地址');
            $table->text('query')->nullable()->comment('请求参数');
            $table->ipAddress('ip');
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
        Schema::dropIfExists('operation_logs');
    }
}
