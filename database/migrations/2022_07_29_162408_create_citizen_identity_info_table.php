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
            $table->id();
            $table->integer('reference_id');
            $table->string('reference_type', 50);
            $table->text('name');
            $table->text('identity')->nullable();
            $table->string('year_of_birth', 10);
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
