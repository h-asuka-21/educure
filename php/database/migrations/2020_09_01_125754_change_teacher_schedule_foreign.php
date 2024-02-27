<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTeacherScheduleForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_schedules', function (Blueprint $table) {
            // Index definitions
            $table->dropForeign('teacher_schedules_schedule_id_foreign');
            $table->dropForeign('teacher_schedules_user_id_foreign');
        });
        Schema::table('teacher_schedules', function (Blueprint $table) {
            $table->foreign('schedule_id', 'schedule_foreign')->references('id')->on('schedules')->onDelete('cascade');
            $table->foreign('user_id', 'user_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_schedules', function (Blueprint $table) {
            $table->dropForeign('schedule_foreign');
            $table->dropForeign('user_foreign');
        });
        Schema::table('teacher_schedules', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
