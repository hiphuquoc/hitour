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
        Schema::create('combo_info', function (Blueprint $table) {
            $table->id();
            $table->integer('seo_id');
            $table->string('code', 20);
            $table->text('name');
            $table->integer('price_show');
            $table->integer('price_del')->nullable();
            // $table->integer('days');
            // $table->integer('nights');
            // $table->string('time_start', 100)->nullable();
            // $table->string('time_end', 100)->nullable();
            $table->text('departure_schedule');
            $table->boolean('status_show')->default(1);
            $table->boolean('status_sidebar')->default(1);
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
        // Schema::dropIfExists('combo_info');
    }
};
