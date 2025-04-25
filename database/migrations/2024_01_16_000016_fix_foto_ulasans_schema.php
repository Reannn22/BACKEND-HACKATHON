<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop and recreate table
        Schema::dropIfExists('foto_ulasans');

        Schema::create('foto_ulasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->onDelete('cascade');
            $table->string('foto_path');
            $table->timestamps();
        });

        // Reset auto increment
        DB::statement('ALTER TABLE foto_ulasans AUTO_INCREMENT = 1');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        Schema::dropIfExists('foto_ulasans');
    }
};
