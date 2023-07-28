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
        Schema::create('comment_info', function (Blueprint $table) {
            $table->id();
            $table->text('reference_type');
            $table->text('reference_id');
            $table->text('title');
            $table->text('comment')->nullable();
            $table->text('author_name');
            $table->text('author_phone')->nullable();
            $table->text('author_email')->nullable();
            $table->string('rating', 3);
            $table->string('rating_for_local', 3)->nullable();
            $table->string('rating_for_clean', 3)->nullable();
            $table->string('rating_for_service', 3)->nullable();
            $table->string('rating_for_value', 3)->nullable();
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
        // Schema::dropIfExists('comment_info');
    }
};
