<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('スコア管理ID');
            $table->unsignedBigInteger('student_id')->comment('受講者ID');
            $table->unsignedBigInteger('test_id')->comment('テストID');
            $table->unsignedTinyInteger('score')->comment('スコア');
            $table->timestamps();

            // Index definitions
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('test_id')->references('id')->on('tests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
