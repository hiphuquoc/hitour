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
        Schema::create('hotel_price', function (Blueprint $table) {
            $table->id();
            $table->integer('hotel_room_id');
            $table->longText('description')->nullable();
            $table->integer('number_people');
            $table->integer('price');
            $table->integer('price_old')->nullable();
            $table->integer('sale_off')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('hotel_price');
    }
};
