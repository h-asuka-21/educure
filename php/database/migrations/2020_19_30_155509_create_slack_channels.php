<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlackChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slack_channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->comment('企業ID');
            $table->string('channel_id')->comment('チャンネルID');
            $table->timestamps();
            $table->foreign('company_id', 'slack_channels_company_id')
                ->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slack_channels');
    }
}
