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
        Schema::create('air_info', function (Blueprint $table) {
            $table->id();
            $table->integer('seo_id');
            $table->text('name');
            $table->integer('air_location_id');
            $table->integer('air_departure_id');
            $table->integer('air_port_location_id');
            $table->integer('air_port_departure_id');
            $table->integer('note')->nullable();
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
        // Schema::dropIfExists('air_info');
    }
};
