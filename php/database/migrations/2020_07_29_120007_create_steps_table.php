<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('ステップID');
            $table->unsignedBigInteger('curriculum_id')->comment('カリキュラムID');
            $table->string('name')->comment('ステップ名');
            $table->text('content')->comment('内容');
            $table->string('image')->nullable()->comment('画像');
            $table->unsignedTinyInteger('target_days')->comment('目標日数');
            $table->unsignedTinyInteger('deadline_days')->comment('デットライン日数');
            $table->timestamps();
            $table->softDeletes();

            // Index definitions
            $table->foreign('curriculum_id')->references('id')->on('curriculums');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steps');
    }
}
