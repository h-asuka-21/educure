<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCurriculums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_curriculums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->comment('コースID');
            $table->unsignedBigInteger('curriculum_id')->comment('カリキュラムID');
            $table->unsignedInteger('order')->comment('順位');
            $table->timestamps();
            $table->foreign('course_id', 'course_curriculums_course_id')
                ->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('curriculum_id', 'course_curriculums_curriculum_id')
                ->references('id')->on('curriculums')->onDelete('cascade');
        });
        Schema::table('curriculums', function (Blueprint $table) {
            $table->dropForeign('curriculums_course_id_foreign');
            $table->dropColumn('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_curriculums');
        Schema::table('curriculums', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->comment('コースID');
        });
    }
}
