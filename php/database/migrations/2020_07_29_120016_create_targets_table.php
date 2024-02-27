<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('目標管理ID');
            $table->unsignedBigInteger('student_id')->comment('受講者ID');
            $table->unsignedBigInteger('step_id')->comment('ステップID');
            $table->unsignedTinyInteger('target_days')->comment('目標設定日');
            $table->timestamps();

            // Index definitions
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('step_id')->references('id')->on('steps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targets');
    }
}
