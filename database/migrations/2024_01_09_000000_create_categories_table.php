<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // Set auto-increment to start from 0
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 0');
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
