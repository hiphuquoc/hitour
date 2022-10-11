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
        Schema::create('air_departure', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('display_name');
            $table->text('description');
            $table->integer('seo_id');
            $table->integer('district_id')->nullable();
            $table->integer('province_id');
            $table->integer('region_id');
            $table->text('note')->nullable();
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
        // Schema::dropIfExists('air_departure');
    }
};
