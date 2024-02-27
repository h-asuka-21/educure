<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStudentTableDatetimeToDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->date('start_date')->nullable()->comment('カリキュラム開始日')->change();
            $table->date('end_date')->nullable()->comment('カリキュラム終了日')->change();
            $table->date('birthday')->nullable()->comment('生年月日')->change();
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
            $table->dateTime('start_date')->nullable()->comment('カリキュラム開始日')->change();
            $table->dateTime('end_date')->nullable()->comment('カリキュラム終了日')->change();
            $table->dateTime('birthday')->nullable()->comment('生年月日')->change();
        });
    }
}
