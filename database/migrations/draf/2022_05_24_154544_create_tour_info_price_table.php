<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourInfoPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_info_price', function (Blueprint $table) {
            $table->id();
            $table->integer('tour_info_id');        // [ref: > tour_info.id]
            $table->integer('price_adult');
            $table->integer('price_child');
            $table->integer('price_old');
            $table->integer('profit_adult')->nullable();
            $table->integer('profit_child')->nullable();
            $table->integer('profit_old')->nullable();
            $table->string('option', 100);
            $table->text('date')->nullable();       // 2022-05-01 | 2022-05-04 | 2022-05-15 > 2022-05-31
            $table->boolean('default')->default(0); // 1: áp dụng tất cả các ngày| 0: xét date
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('tour_info_price');
    }
}
