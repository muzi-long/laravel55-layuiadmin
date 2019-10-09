<?php

use Illuminate\Support\Facades\Schema;
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
            $table->increments('id')->comment('自增编号');
            $table->string('username')->unique()->comment('登录账号');
            $table->string('phone')->unique()->comment('联系电话');
            $table->string('realname')->comment('真实姓名');
            $table->string('email')->unique()->comment('电子邮箱');
            $table->string('password')->comment('登录密码');
            $table->rememberToken()->comment('保持登录');
            $table->uuid('uuid')->comment('唯一编号');
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
        Schema::dropIfExists('users');
    }
}
