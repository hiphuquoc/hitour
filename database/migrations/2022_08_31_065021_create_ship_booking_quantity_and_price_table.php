<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_booking_quantity_and_price', function (Blueprint $table) {
            $table->id();
            $table->integer('ship_booking_id');
            $table->string('time_departure', 10);
            $table->string('time_arrive', 10);
            $table->date('date');
            $table->string('port_departure', 50);
            $table->string('port_departure_address', 100);
            $table->string('port_departure_district', 50);
            $table->string('port_departure_province', 50);
            $table->string('port_location', 50);
            $table->string('port_location_address', 100);
            $table->string('port_location_district', 50);
            $table->string('port_location_province', 50);
            $table->string('departure', 50);
            $table->string('location', 50);
            $table->integer('quantity_adult')->nullable();
            $table->integer('quantity_child')->nullable();
            $table->integer('quantity_old')->nullable();
            $table->string('partner_name', 100);
            $table->integer('price_adult')->nullable();
            $table->integer('price_child')->nullable();
            $table->integer('price_old')->nullable();
            $table->string('type', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('ship_booking_quantity_and_price');
    }
};
