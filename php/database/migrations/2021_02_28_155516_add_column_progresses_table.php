<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('progresses', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress_status')->after('percent')->nullable()->comment('進捗管理状態');
            $table->unsignedTinyInteger('application_flg')->after('progress_status')->default(0)->comment('申請フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('progresses', function (Blueprint $table) {
            $table->dropColumn('progress_status');
            $table->dropColumn('application_flg');
        });
    }
}
