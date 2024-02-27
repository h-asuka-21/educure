<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('日報管理ID');
            $table->unsignedBigInteger('reservation_id')->comment('カリキュラム予約ID');
            $table->unsignedBigInteger('student_id')->comment('受講者ID');
            $table->unsignedTinyInteger('personal_evaluation')->comment('個人評価');
            $table->string('worked')->comment('取り組んだこと');
            $table->text('note')->comment('自由記述');
            $table->timestamps();

            // Index definitions
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
