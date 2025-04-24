<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('items_detail');

        Schema::create('items_detail', function (Blueprint $table) {
            $table->id();
            $table->string('nama_item_detail')->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE items_detail AUTO_INCREMENT = 1');
    }

    public function down()
    {
        Schema::dropIfExists('items_detail');
    }
};
