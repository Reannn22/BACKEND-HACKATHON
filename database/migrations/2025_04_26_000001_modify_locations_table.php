<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            // Drop all columns except id
            $table->dropColumn([
                'kode_lokasi',
                'gedung',
                'ruangan',
                'created_at',
                'updated_at'
            ]);

            // Add nama_lokasi if it doesn't exist
            if (!Schema::hasColumn('locations', 'nama_lokasi')) {
                $table->string('nama_lokasi');
            }
        });
    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('kode_lokasi')->nullable();
            $table->string('gedung')->nullable();
            $table->string('ruangan')->nullable();
            $table->timestamps();
        });
    }
};
