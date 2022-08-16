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
        Schema::create('ship_time_and_price', function (Blueprint $table) {
            $table->id();
            $table->integer('ship_info_id');
            $table->integer('ship_partner_id');
            // $table->string('time_departure', 20);
            // $table->string('time_arrive', 20);
            // $table->integer('time_move');
            $table->date('date');
            $table->integer('price_adult');
            $table->integer('price_child');
            $table->integer('price_old');
            $table->integer('price_vip');
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
        // Schema::dropIfExists('ship_time');
    }
};
