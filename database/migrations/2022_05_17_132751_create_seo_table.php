<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('image_small')->nullable();
            $table->integer('level');
            $table->integer('parent')->nullable();
            $table->integer('ordering')->nullable();
            $table->integer('topic')->nullable();
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('slug');
            $table->string('type', '100');
            $table->string('rating_author_name', 1)->nullable();
            $table->string('rating_author_star', 6)->default(5);
            $table->integer('rating_aggregate_count')->nullable();
            $table->string('rating_aggregate_star', 6)->nullable();
            $table->integer('created_by');
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
        // Schema::dropIfExists('pages');
    }
}
