<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('items');

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nama_item')->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1');
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
