<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE progresses MODIFY COLUMN reservation_id BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT '出席予約ID' AFTER percent");
        DB::statement("ALTER TABLE progresses MODIFY COLUMN date DATE NOT NULL COMMENT '評価日' AFTER reservation_id");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE progresses MODIFY COLUMN reservation_id BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT '出席予約ID' AFTER deleted_at");
        DB::statement("ALTER TABLE progresses MODIFY COLUMN date DATE NOT NULL COMMENT '評価日' AFTER reservation_id");
    }
}
