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
        Schema::create('hotel_booking_quantity_and_price', function (Blueprint $table) {
            $table->id();
            $table->integer('hotel_booking_id');
            $table->integer('hotel_info_id');
            $table->integer('hotel_room_id');
            $table->integer('hotel_price_id');
            $table->integer('quantity')->default(1);
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('number_night')->default(1);
            $table->text('hotel_room_name');
            $table->integer('hotel_room_size')->nullable();
            $table->text('hotel_price_description')->nullable();
            $table->integer('hotel_price_number_people');
            $table->integer('hotel_price_price');
            $table->integer('hotel_price_price_old')->nullable();
            $table->integer('hotel_price_sale_off')->nullable();
            $table->text('hotel_price_bed');
            $table->boolean('hotel_price_breakfast')->default(0);
            $table->boolean('hotel_price_given')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('hotel_booking_quantity_and_price');
    }
};
