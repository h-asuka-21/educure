<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('テスト管理ID');
            $table->string('name')->comment('テスト名');
            $table->unsignedBigInteger('course_id')->nullable()->comment('コースID');
            $table->unsignedBigInteger('curriculum_id')->nullable()->comment('カリキュラムID');
            $table->unsignedTinyInteger('test_type')->comment('テスト種別');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
