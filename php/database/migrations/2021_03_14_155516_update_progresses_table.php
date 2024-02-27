<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `progresses` SET `progress_status` = 3 WHERE `percent` = 100");
        DB::statement("UPDATE `progresses` SET `progress_status` = 1 WHERE `percent` <> 100");
    }
}
