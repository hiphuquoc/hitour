<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_info', function (Blueprint $table) {
            $table->id();
            $table->integer('seo_id');         // [ref: > seo.id]
            $table->integer('departure_id')->nullable();    /* tour khởi hành từ đâu */
            $table->string('pick_up', 255)->nullable();
            $table->string('code', 20);
            $table->text('name');
            $table->integer('price_show');
            $table->integer('price_del')->nullable();
            $table->integer('days');
            $table->integer('nights');
            $table->boolean('status_show')->default(1);
            $table->boolean('status_sidebar')->default(1);
            $table->longText('content')->nullable();
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
        // Schema::dropIfExists('tour_info');
    }
}
