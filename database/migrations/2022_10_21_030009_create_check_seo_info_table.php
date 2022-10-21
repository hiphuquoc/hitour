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
        Schema::create('check_seo_info', function (Blueprint $table) {
            $table->id();
            $table->integer('seo_id');
            $table->string('name', 50); /* h2 | h3 | a | img */
            $table->string('type', 50); /* heading | image | link */
            $table->text('text')->nullable();
            $table->text('href')->nullable();
            $table->text('src')->nullable();
            $table->text('alt')->nullable();
            $table->text('title')->nullable();
            $table->text('error')->nullable();
            $table->integer('error_type')->nullable(); /* 1 | 2 | 3 */ 
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
        // Schema::dropIfExists('check_seo_info');
    }
};
