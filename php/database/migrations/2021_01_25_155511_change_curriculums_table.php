<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeCurriculumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE curriculums MODIFY COLUMN test_id BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT 'テストID' AFTER zip_name");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE curriculums MODIFY COLUMN test_id BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT 'テストID' AFTER deleted_at");
    }
}
