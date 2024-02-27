<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_histories', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('面談履歴ID');
            $table->unsignedBigInteger('student_id')->comment('受講者ID');
            $table->unsignedTinyInteger('sales_evaluation')->default(3)->comment('営業評価');
            $table->text('evaluation_reason')->nullable()->comment('評価備考');
            $table->unsignedBigInteger('user_id')->comment('評価者ID');
            $table->timestamps();

            // Index definitions
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_histories');
    }
}
