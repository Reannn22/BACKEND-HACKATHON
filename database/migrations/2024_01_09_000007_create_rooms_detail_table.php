<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('rooms_detail');

        Schema::create('rooms_detail', function (Blueprint $table) {
            $table->id();
            $table->string('nama_room_detail')->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE rooms_detail AUTO_INCREMENT = 1');
    }

    public function down()
    {
        Schema::dropIfExists('rooms_detail');
    }
};
