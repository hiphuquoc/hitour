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
        Schema::create('service_price', function (Blueprint $table) {
            $table->id();
            $table->integer('service_option_id');
            $table->string('apply_age', 100);
            $table->integer('price');
            $table->integer('profit');
            $table->date('date_start');
            $table->date('date_end');
            $table->longText('promotion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('service_price');
    }
};
