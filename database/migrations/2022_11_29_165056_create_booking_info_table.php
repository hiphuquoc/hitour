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
        Schema::create('booking_info', function (Blueprint $table) {
            $table->id();
            $table->string('no', 20);
            $table->integer('customer_info_id');
            $table->integer('reference_id');
            $table->string('type', 50);
            $table->integer('status_id')->default(1);
            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->integer('required_deposit')->nullable();
            $table->integer('paid')->nullable();
            $table->dateTime('expiration_at')->nullable();
            $table->longText('note_customer')->nullable();
            $table->integer('created_by')->default(0);
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
        // Schema::dropIfExists('booking_info');
    }
};
