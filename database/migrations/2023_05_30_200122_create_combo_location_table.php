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
        Schema::create('combo_location', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('display_name');
            $table->text('description');
            $table->integer('seo_id');         // [ref: > seo.id]
            $table->integer('district_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('region_id')->nullable();
            $table->boolean('island')->default(0);
            $table->boolean('special')->default(0);
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
        // Schema::dropIfExists('combo_location');
    }
};
