<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('設問ID');
            $table->string('name')->comment('設問名');
            $table->unsignedBigInteger('test_id')->comment('テストID');
            $table->text('content')->comment('内容');
            $table->string('image')->nullable()->comment('画像');
            $table->string('choice1')->nullable()->comment('選択肢1');
            $table->string('choice2')->nullable()->comment('選択肢2');
            $table->string('choice3')->nullable()->comment('選択肢3');
            $table->string('choice4')->nullable()->comment('選択肢4');
            $table->unsignedTinyInteger('answer')->comment('解答');
            $table->timestamps();

            // Index definitions
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
