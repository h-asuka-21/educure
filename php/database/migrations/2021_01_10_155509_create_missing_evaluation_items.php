<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissingEvaluationItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missing_evaluation_items', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->id()->comment('評価不足項目ID');
            $table->unsignedBigInteger('student_id')->comment('受講者ID');
            $table->unsignedTinyInteger('missing_type')->comment('不足種別');
            $table->string('reason')->comment('不足理由');
            $table->timestamps();

            // Index definitions
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('missing_evaluation_items');
    }
}
