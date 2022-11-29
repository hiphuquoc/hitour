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
        Schema::create('service_booking_quantity_and_price', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_info_id');
            $table->string('option_name');
            $table->string('option_age');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('profit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('service_booking_quantity_and_price');
    }
};
