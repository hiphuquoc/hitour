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
        Schema::create('citizen_identity_info', function (Blueprint $table) {
            /* tour_booking|ship|room|service */
            $table->id();
            $table->integer('booking_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_identity')->nullable();
            $table->string('customer_year_of_birth');
            $table->boolean('customer_sex')->nullable();
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
        // Schema::dropIfExists('citizen_identity_info');
    }
};
