<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('payments');

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_payment')->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE payments AUTO_INCREMENT = 1');
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
