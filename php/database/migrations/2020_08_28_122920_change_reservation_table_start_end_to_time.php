<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeReservationTableStartEndToTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->time('start_time')->comment('出席予約時刻')->change();
            $table->time('end_time')->comment('退席予約時刻')->change();
            $table->dropColumn('teacher_evaluation');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedTinyInteger('teacher_evaluation')->nullable()->comment('講師評価');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dateTime('start_time')->comment('出席予約時刻')->change();
            $table->dateTime('end_time')->comment('退席予約時刻')->change();
            $table->dropColumn('teacher_evaluation');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedTinyInteger('teacher_evaluation')->default(3)->comment('講師評価');
        });
    }
}
