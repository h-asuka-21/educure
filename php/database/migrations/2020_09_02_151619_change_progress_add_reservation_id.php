<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProgressAddReservationId extends Migration
{
    const FOREIGN = 'reservation_foreign';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('progresses', function (Blueprint $table) {
            $table->unsignedBigInteger('reservation_id')->nullable()->comment('出席予約ID');
            $table->index('reservation_id', 'progress_reservation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('progresses', function (Blueprint $table) {
            $table->dropIndex('progress_reservation_id');
            $table->dropColumn('reservation_id');
        });
    }
}
