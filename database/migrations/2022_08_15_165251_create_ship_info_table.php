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
        Schema::create('ship_info', function (Blueprint $table) {
            $table->id();
            $table->integer('seo_id');
            $table->string('name', 255);
            $table->integer('ship_location_id');
            $table->integer('ship_departure_id');
            $table->integer('note')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('ship_info');
    }
};
