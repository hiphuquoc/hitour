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
        Schema::create('tour_country', function (Blueprint $table) {
            $table->id();
            $table->text('tour_continent_id');
            $table->text('name');
            $table->text('display_name');
            $table->text('description');
            $table->integer('seo_id');
            $table->boolean('island')->default(0);
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
        // Schema::dropIfExists('tour_country');
    }
};
