<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipBrandManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_brand_manager', function (Blueprint $table) {
            $table->id();
            $table->string('login', 100);
            $table->string('password', 100);
            $table->integer('ship_brand_id');       // [ref: < ship_brand.id]
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('ship_brand_manager');
    }
}
