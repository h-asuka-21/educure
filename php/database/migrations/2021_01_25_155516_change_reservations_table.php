<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `reservations` CHANGE `teacher_evaluation` `teacher_evaluation` TINYINT(3) UNSIGNED NULL DEFAULT NULL COMMENT '講師評価' AFTER `evaluation_reason`");
        DB::statement("ALTER TABLE `reservations` CHANGE `attendance_flg` `attendance_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '出席フラグ' AFTER `teacher_evaluation`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `reservations` CHANGE `teacher_evaluation` `teacher_evaluation` TINYINT(3) UNSIGNED NULL DEFAULT NULL COMMENT '講師評価' AFTER `deleted_at`");
        DB::statement("ALTER TABLE `reservations` CHANGE `attendance_flg` `attendance_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '出席フラグ' AFTER `teacher_evaluation`");
    }
}
