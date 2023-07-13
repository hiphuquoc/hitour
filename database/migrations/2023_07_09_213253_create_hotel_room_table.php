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
        Schema::create('hotel_room', function (Blueprint $table) {
            $table->id();
            $table->integer('hotel_info_id');
            $table->text('name');
            $table->text('condition')->nullable(); /* giường trong phòng - bao gồm ăn sang hay chưa - các điều khoản thanh toán */
            $table->integer('size');
            $table->integer('number_people');
            $table->integer('price');
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('hotel_room');
    }
};
