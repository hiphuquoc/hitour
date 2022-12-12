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
        Schema::create('detail_more_less', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('value');
            $table->string('reference_type');
            $table->integer('reference_id');
            $table->integer('created_by');
            $table->longText('note')->nullable();
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
        // Schema::dropIfExists('detail_more_less');
    }
};
