<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('locations');

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
            $table->string('kode_lokasi');
            $table->string('gedung');
            $table->string('lantai');
            $table->string('ruangan');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};
