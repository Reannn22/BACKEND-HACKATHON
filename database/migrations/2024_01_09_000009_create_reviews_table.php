<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('reviews');

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('nama_review')->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE reviews AUTO_INCREMENT = 1');
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
