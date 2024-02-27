<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEvaluationStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->double('total_score', 3, 1)->after('after_graduation_flg')->nullable()->comment('評価合計');
            $table->double('teacher_score', 2, 1)->after('total_score')->nullable()->comment('講師評価');
            $table->double('sales_score', 2, 1)->after('teacher_score')->nullable()->comment('営業評価');
            $table->double('comprehension_score', 2, 1)->after('sales_score')->nullable()->comment('理解度');
            $table->double('think_score', 2, 1)->after('comprehension_score')->nullable()->comment('思考力');
            $table->double('attendance_score', 2, 1)->after('think_score')->nullable()->comment('出席率');
            $table->double('report_score', 2, 1)->after('attendance_score')->nullable()->comment('報告率');
            $table->double('progress_score', 2, 1)->after('report_score')->nullable()->comment('進捗率');
            $table->date('aggregate_date')->after('progress_score')->nullable()->comment('集計日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('total_score');
            $table->dropColumn('teacher_score');
            $table->dropColumn('sales_score');
            $table->dropColumn('comprehension_score');
            $table->dropColumn('think_score');
            $table->dropColumn('attendance_score');
            $table->dropColumn('report_score');
            $table->dropColumn('progress_score');
            $table->dropColumn('aggregate_date');
        });
    }
}
