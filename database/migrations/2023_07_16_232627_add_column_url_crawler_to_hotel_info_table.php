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
        Schema::table('hotel_info', function (Blueprint $table) {
            $table->text('url_crawler_mytour')->nullable();
            $table->text('url_crawler_tripadvisor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('hotel_info', function (Blueprint $table) {
        //     //
        // });
    }
};
