<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('room_categories');

        Schema::create('room_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE room_categories AUTO_INCREMENT = 1');
    }

    public function down()
    {
        Schema::dropIfExists('room_categories');
    }
};
