<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourBookingStatusActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_booking_status_action', function (Blueprint $table) {
            $table->id();
            $table->integer('tour_booking_status_id');      // [ref: > tour_booking_status.id]
            $table->integer('tour_booking_action_id');      // [ref: > tour_booking_action.id]
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('tour_booking_status_action');
    }
}
