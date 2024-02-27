<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `questions` CHANGE `order` `order` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '順序' AFTER `answer`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `questions` CHANGE `order` `order` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '順序' AFTER `updated_at`");
    }
}
