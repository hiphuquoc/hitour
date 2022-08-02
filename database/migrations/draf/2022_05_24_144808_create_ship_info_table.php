<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipInfoTable extends Migration
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
            $table->integer('page_id');             // [ref: - pages.id]
            $table->integer('province_to_id');      // [ref: - provinces.id]
            $table->integer('district_to_id');      // [ref: - districts.id]
            $table->integer('province_from_id');    // [ref: - provinces.id]
            $table->integer('district_from_id');    // [ref: - districts.id]
            $table->integer('distance')->nullable();
            $table->integer('ship_brand_id');       // [ref: > ship_brand.id]
            $table->string('type', 15);                // active | inactive
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
}
