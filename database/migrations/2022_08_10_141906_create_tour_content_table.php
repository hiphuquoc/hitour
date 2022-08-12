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
        Schema::create('tour_content', function (Blueprint $table) {
            $table->id();
            $table->integer('tour_info_id');
            $table->longText('special_content')->nullable();
            $table->longText('special_list')->nullable();
            $table->longText('include')->nullable();
            $table->longText('not_include')->nullable();
            $table->longText('policy_child')->nullable();
            $table->longText('menu')->nullable();
            $table->longText('hotel')->nullable();
            $table->longText('policy_cancel')->nullable();
            $table->longText('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('tour_content');
    }
};
