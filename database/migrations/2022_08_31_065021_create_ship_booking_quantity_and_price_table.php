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
            $table->integer('ship_info_id');
            $table->string('name', 50);
            $table->dateTime('date');
            $table->string('port_departure', 50);
            $table->string('port_location', 50);
            $table->string('departure', 50);
            $table->string('location', 50);
            $table->integer('quantity_adult')->nullable();
            $table->integer('quantity_child')->nullable();
            $table->integer('quantity_old')->nullable();
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
