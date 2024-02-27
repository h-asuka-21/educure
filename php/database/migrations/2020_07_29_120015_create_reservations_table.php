<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('予約ID');
            $table->unsignedBigInteger('student_id')->comment('受講者ID');
            $table->unsignedBigInteger('schedule_id')->comment('スケジュールID');
            $table->dateTime('start_time')->nullable()->comment('出席日時');
            $table->dateTime('end_time')->nullable()->comment('退席日時');
            $table->string('reason')->nullable()->comment('遅刻・早退理由');
            $table->unsignedTinyInteger('teacher_evaluation')->default(3)->comment('講師評価');
            $table->text('evaluation_reason')->nullable()->comment('評価備考');
            $table->timestamps();
            $table->softDeletes();

            // Index definitions
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('schedule_id')->references('id')->on('schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
