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
        Schema::create('tour_timetable_foreign', function (Blueprint $table) {
            $table->id();
            $table->integer('tour_info_foreign_id');
            $table->text('title');
            $table->longText('content');
            $table->longText('content_sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('tour_timetable_foreign');
    }
};
