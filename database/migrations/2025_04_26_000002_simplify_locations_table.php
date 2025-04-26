<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Drop and recreate locations table
        Schema::dropIfExists('locations');
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
        });

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Restore original table structure
        Schema::dropIfExists('locations');
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lokasi');
            $table->string('nama_lokasi');
            $table->string('gedung');
            $table->string('ruangan');
            $table->timestamps();
        });

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
};
