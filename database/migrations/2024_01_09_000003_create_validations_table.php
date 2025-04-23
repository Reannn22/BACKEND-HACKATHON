<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('validations');

        Schema::create('validations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_validation')->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE validations AUTO_INCREMENT = 1');
    }

    public function down()
    {
        Schema::dropIfExists('validations');
    }
};
