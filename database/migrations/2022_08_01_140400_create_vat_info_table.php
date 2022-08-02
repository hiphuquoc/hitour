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
        Schema::create('vat_info', function (Blueprint $table) {
            $table->id();
            $table->string('reference_type', 50);
            $table->integer('reference_id');
            $table->string('vat_name');
            $table->string('vat_code');
            $table->string('vat_address');
            $table->text('vat_path')->nullable();
            $table->text('vat_note')->nullable();
            $table->integer('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('vat_info');
    }
};
