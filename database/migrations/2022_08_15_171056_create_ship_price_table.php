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
        Schema::create('ship_price', function (Blueprint $table) {
            $table->id();
            $table->integer('ship_info_id');
            $table->integer('ship_partner_id');
            $table->date('date_start');
            $table->integer('price_adult');
            $table->integer('price_child');
            $table->integer('price_old');
            $table->integer('price_vip')->nullable();
            $table->integer('profit_percent');
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
        // Schema::dropIfExists('ship_price');
    }
};
