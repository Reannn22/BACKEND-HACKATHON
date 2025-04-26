<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'kondisi_barang')) {
                $table->enum('kondisi_barang', ['baru', 'baik', 'rusak ringan', 'rusak berat'])
                      ->after('warna_barang')
                      ->default('baik');
            }
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('kondisi_barang');
        });
    }
};
