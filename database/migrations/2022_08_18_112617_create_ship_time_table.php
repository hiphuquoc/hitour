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
        Schema::create('ship_time', function (Blueprint $table) {
            $table->id();
            $table->integer('ship_price_id');
            $table->string('time_departure', 20);
            $table->string('time_arrive', 20);
            $table->string('time_move', 20);
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
