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
        Schema::create('service_info', function (Blueprint $table) {
            $table->id();
            $table->integer('seo_id');
            $table->integer('tour_location_id');
            $table->string('code', 20);
            $table->text('name');
            $table->integer('price_show');
            $table->integer('price_del')->nullable();
            $table->string('time_start', 100)->nullable();
            $table->string('time_end', 100)->nullable();
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
        // Schema::dropIfExists('service_info');
    }
};
