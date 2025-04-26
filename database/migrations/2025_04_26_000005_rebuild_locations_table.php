<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, drop foreign key constraint from items table
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['id_lokasi']);
        });

        // Drop and recreate locations table
        Schema::dropIfExists('locations');
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
        });

        // Re-add foreign key constraint to items table
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('id_lokasi')->references('id')->on('locations');
        });
    }

    public function down()
    {
        // Drop foreign key if we need to rollback
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['id_lokasi']);
        });
    }
};
