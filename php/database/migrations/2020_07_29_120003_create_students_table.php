<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            // Default Settings
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id')->comment('受講者ID');
            $table->unsignedBigInteger('company_id')->comment('企業ID');
            $table->string('name')->comment('名前');
            $table->string('name_kana')->nullable()->comment('名前(カナ)');
            $table->string('password')->comment('パスワード');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->rememberToken()->comment('再発行トークン');
            $table->unsignedBigInteger('course_id')->comment('コースID');
            $table->dateTime('start_date')->nullable()->comment('カリキュラム開始日');
            $table->dateTime('end_date')->nullable()->comment('カリキュラム終了日');
            $table->dateTime('birthday')->nullable()->comment('生年月日');
            $table->unsignedTinyInteger('gender')->nullable()->comment('性別');
            $table->unsignedTinyInteger('academic_type')->nullable()->comment('最終学歴');
            $table->unsignedTinyInteger('birthplace')->nullable()->comment('出身地');
            $table->unsignedTinyInteger('working_history')->nullable()->comment('社会人歴');
            $table->unsignedTinyInteger('former_job_type')->nullable()->comment('前職');
            $table->unsignedTinyInteger('former_job_status')->nullable()->comment('前職雇用形態');
            $table->unsignedTinyInteger('change_job_count')->nullable()->comment('転職回数');
            $table->unsignedTinyInteger('national_qualification_flg')->default(0)->comment('国家資格有無');
            $table->unsignedTinyInteger('qualification_flg')->default(0)->comment('資格有無');
            $table->unsignedTinyInteger('club_type')->default(0)->comment('部活動経験有無');
            $table->unsignedTinyInteger('after_graduation_flg')->default(0)->comment('卒業後進路');
            $table->timestamps();

            // Index definitions
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
