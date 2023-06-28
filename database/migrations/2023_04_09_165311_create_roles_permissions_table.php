<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');

            // //FOREIGN KEY
            // $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            // $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            // //PRIMARY KEYS
            // $table->primary(['role_id','permission_id']);
        });
    }

    public function down()
    {
        // Schema::dropIfExists('roles_permissions');
    }
};
