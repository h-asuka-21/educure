<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatetimeSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->string('name')->after('company_id')->comment('スケジュール名');
            $table->date('date')->after('name')->comment('日付');
            $table->time('start_time')->after('date')->comment('開始時間');
            $table->time('end_time')->after('start_time')->comment('終了時間');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('date');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dateTime('start_date')->comment('開始日時');
            $table->dateTime('end_date')->comment('終了日時');
        });
    }
}
