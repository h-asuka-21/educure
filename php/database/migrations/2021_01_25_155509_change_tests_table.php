<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('course_id');
            $table->dropColumn('curriculum_id');
        });

        DB::statement("ALTER TABLE tests MODIFY COLUMN test_time INT(11) NOT NULL COMMENT 'テスト時間' AFTER test_type");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->comment('コースID');
            $table->unsignedBigInteger('curriculum_id')->nullable()->comment('カリキュラムID');
        });

        DB::statement("ALTER TABLE tests MODIFY COLUMN test_time INT(11) NOT NULL COMMENT 'テスト時間' AFTER updated_at");
    }
}
