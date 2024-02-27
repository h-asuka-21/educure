<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('ユーザーID');
            $table->unsignedBigInteger('company_id')->comment('企業ID');
            $table->string('name')->comment('名前');
            $table->string('name_kana')->nullable()->comment('名前(カナ)');
            $table->string('password')->comment('パスワード');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->rememberToken()->comment('再発行トークン');
            $table->unsignedTinyInteger('role')->default(0)->comment('権限');
            $table->timestamps();
            // Index definitions
            $table->foreign('company_id')->references('id')->on('companies');
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
