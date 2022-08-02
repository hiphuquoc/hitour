<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipInfoPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_info_price', function (Blueprint $table) {
            $table->id();
            $table->integer('price_adult')->nullable();
            $table->integer('price_child')->nullable();
            $table->integer('price_old')->nullable();
            $table->integer('price_orther')->nullable();
            $table->integer('price_vip')->nullable();
            $table->string('type', 20);                // all_day | begin_week | end_week | some_day
            $table->mediumText('day');                 // NULL => nếu some_day thì là từng ngày hoặc ngày trong khoảng
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('ship_info_price');
    }
}
