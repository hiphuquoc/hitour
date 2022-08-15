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
        Schema::create('ship_partner', function (Blueprint $table) {
            $table->id();
            $table->integer('seo_id');
            $table->string('name');
            $table->text('company_name')->nullable();
            $table->string('company_code')->nullable();
            $table->text('company_address')->nullable();
            $table->text('company_website')->nullable();
            $table->string('company_hotline')->nullable();
            $table->string('company_email')->nullable();
            $table->text('company_logo')->nullable();
            $table->integer('created_by')->nullable();
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
        // Schema::dropIfExists('ship_partner');
    }
};
