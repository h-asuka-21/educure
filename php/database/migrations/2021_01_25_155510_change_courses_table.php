<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE courses MODIFY COLUMN general_test_id BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT '総合テストID' AFTER name");
        DB::statement("ALTER TABLE courses MODIFY COLUMN first_test_id BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT 'CABテストID' AFTER general_test_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE courses MODIFY COLUMN general_test_id BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT '総合テストID' AFTER deleted_at");
        DB::statement("ALTER TABLE courses MODIFY COLUMN first_test_id BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT 'CABテストID' AFTER general_test_id");
    }
}
