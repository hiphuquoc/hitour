<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipBrandContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_brand_contact', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('ship_brand_id');           // [ref: < ship_brand.id]
            $table->text('address')->nullable();
            $table->integer('province_id');             // [ref: - provinces.id]
            $table->integer('district_id');             // [ref: - districts.id]
            $table->integer('town_id');                 // [ref: - towns.id]
            $table->string('phone', 15)->nullable();
            $table->string('hotline', 15)->nullable();
            $table->text('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('ship_brand_contact');
    }
}
