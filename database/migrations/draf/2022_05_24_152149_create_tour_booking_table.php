<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_booking', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('tour_info_id');        // [ref: > customers.id]
            $table->integer('status_id');           // [ref: > tour_info.id]
            $table->text('option')->nullable();              
            $table->integer('ordering')->nullable();
            $table->string('departure_time', 50)->nullable();
            $table->integer('qty_adult')->default(0);
            $table->integer('qty_child')->default(0);
            $table->integer('qty_old')->default(0);
            $table->integer('qty_orther')->default(0);
            $table->integer('price_adult')->nullable();
            $table->integer('price_child')->nullable();
            $table->integer('price_old')->nullable();
            $table->integer('price_orther')->nullable();
            $table->text('note_customer')->nullable();
            $table->integer('created_by')->nullable();
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
        // Schema::dropIfExists('tour_booking');
    }
}
