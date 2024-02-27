<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `steps` CHANGE `order` `order` INT(10) UNSIGNED NOT NULL COMMENT '順序' AFTER `deadline_days`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `steps` CHANGE `order` `order` INT(10) UNSIGNED NOT NULL COMMENT '順序' AFTER `deleted_at`");
    }
}
