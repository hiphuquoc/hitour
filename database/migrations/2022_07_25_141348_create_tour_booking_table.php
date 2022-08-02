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
        Schema::create('tour_booking', function (Blueprint $table) {
            $table->id();
            $table->string('no', 15);
            $table->integer('customer_info_id');
            $table->integer('tour_info_id');
            $table->integer('tour_booking_status_id')->default(1); /* mặc định trạng thái Chờ xác nhận */
            $table->integer('tour_option_id')->nullable();
            $table->date('departure_day');
            $table->integer('require_deposit')->nullable();
            $table->integer('paid')->default(0);
            $table->longText('note_customer')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->dateTime('expiration_at')->nullable(); /* ngày hết hạn */
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
};
