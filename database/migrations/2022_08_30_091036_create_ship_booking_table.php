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
        Schema::create('ship_booking', function (Blueprint $table) {
            $table->id();
            $table->string('no', 20);
            $table->integer('customer_info_id');
            $table->integer('ship_info_id');
            $table->integer('ship_booking_status_id');
            $table->integer('paid');
            $table->text('note_customer');
            $table->integer('created_by');
            $table->dateTime('expiration_at');
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
};
