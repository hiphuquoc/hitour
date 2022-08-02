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
        Schema::create('system_file', function (Blueprint $table) {
            $table->id();
            $table->integer('attachment_id');
            $table->string('relation_table');
            $table->text('file_name')->nullable();
            $table->text('file_path')->nullable();
            $table->text('file_extension')->nullable();
            $table->string('file_type')->nullable();
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
        // Schema::dropIfExists('system_file');
    }
};
