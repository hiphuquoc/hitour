<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_booking', function (Blueprint $table) {
            $table->id();
            $table->integer('customers_id');                // [ref: > customers.id]
            $table->integer('ship_info_id');                // [ref: - ship_info.id]
            $table->integer('status_id');                   // [ref: > ship_booking_status.id]
            $table->string('option', 10);                   // VIP | ECO
            $table->integer('ordering');                    // null
            $table->string('departure_time', 10);           // 07:20
            $table->integer('qty_adult')->default(0);       
            $table->integer('qty_child')->default(0);
            $table->integer('qty_old')->default(0);      
            $table->integer('qty_orther')->default(0);
            $table->integer('price_adult')->nullable();     
            $table->integer('price_child')->nullable();     
            $table->integer('price_old')->nullable();
            $table->integer('price_orther')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('ship_booking');
    }
}
