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
        Schema::create('hotel_info', function (Blueprint $table) {
            $table->id();
            $table->integer('seo_id');
            $table->integer('hotel_location_id');
            $table->text('name');
            $table->string('code', 20)->nullable();
            $table->text('company_name')->nullable();
            $table->string('company_code')->nullable();
            $table->text('address')->nullable();
            $table->text('website')->nullable();
            $table->string('hotline')->nullable();
            $table->string('email')->nullable();
            $table->text('logo')->nullable();
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
        // Schema::dropIfExists('hotel_info');
    }
};
