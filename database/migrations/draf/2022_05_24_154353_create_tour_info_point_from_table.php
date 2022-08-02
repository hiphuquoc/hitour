<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourInfoPointFromTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_info_point_from', function (Blueprint $table) {
            $table->integer('tour_info_id');        // [ref: > tour_info.id]
            $table->integer('region_id');           // [ref: > regions.id]
            $table->integer('province_id');         // [ref: > provinces.id]
            $table->integer('district_id');         // [ref: > districts.id]
            $table->integer('ordering');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('tour_info_point_from');
    }
}
