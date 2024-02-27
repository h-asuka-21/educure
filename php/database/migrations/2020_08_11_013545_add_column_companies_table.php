<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedTinyInteger('industry')->nullable()->after('name')->comment('職種');
            $table->unsignedMediumInteger('number_of_employees')->nullable()->after('industry')->comment('従業員数');
            $table->unsignedTinyInteger('year_of_establishment')->nullable()->after('number_of_employees')->comment('設立年');
            $table->unsignedTinyInteger('average_age')->nullable()->after('year_of_establishment')->comment('平均年齢');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('industry');
            $table->dropColumn('number_of_employees');
            $table->dropColumn('year_of_establishment');
            $table->dropColumn('average_age');
        });
    }
}
