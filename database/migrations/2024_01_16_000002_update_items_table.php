<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('merk_barang', 'merek_barang');
            $table->year('tahun_pengadaan')->after('merek_barang');
            $table->string('gambar_barang')->after('tahun_pengadaan');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('merek_barang', 'merk_barang');
            $table->dropColumn(['tahun_pengadaan', 'gambar_barang']);
        });
    }
};
