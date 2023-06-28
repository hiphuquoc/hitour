<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users_permissions', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');

            // //FOREIGN KEY
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            // //PRIMARY KEYS
            // $table->primary(['user_id','permission_id']);
        });
    }

    public function down()
    {
        // Schema::dropIfExists('users_permissions');
    }
};
