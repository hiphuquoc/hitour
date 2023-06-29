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
        Schema::create('combo_price', function (Blueprint $table) {
            $table->id();
            $table->integer('departure_id');
            $table->integer('combo_option_id');
            $table->string('apply_age');
            $table->integer('price');
            $table->integer('profit')->nullable();
            $table->date('date_start');
            $table->date('date_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('combo_price');
    }
};
