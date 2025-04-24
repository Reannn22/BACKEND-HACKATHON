<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('activities_log');

        Schema::create('activities_log', function (Blueprint $table) {
            $table->id();
            $table->string('nama_activity')->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE activities_log AUTO_INCREMENT = 1');
    }

    public function down()
    {
        Schema::dropIfExists('activities_log');
    }
};
