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
        Schema::table('ship_time', function (Blueprint $table) {
            $table->string('name', 100);
            $table->string('ship_from', 50);
            $table->string('ship_from_sort', 10);
            $table->string('ship_to', 50);
            $table->string('ship_to_sort', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ship_time', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('ship_from');
            $table->dropColumn('ship_from_sort');
            $table->dropColumn('ship_to');
            $table->dropColumn('ship_to_sort');
        });
    }
};
