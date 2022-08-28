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
        Schema::table('ship_info', function (Blueprint $table) {
            $table->integer('ship_port_departure_id');
            $table->integer('ship_port_location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ship_info', function (Blueprint $table) {
            $table->dropColumn('ship_port_departure_id');
            $table->dropColumn('ship_port_location_id');
        });
    }
};
