<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->text('deskripsi_barang')->after('gambar_barang');
            $table->integer('jumlah_total')->after('deskripsi_barang');
            $table->integer('jumlah_tersedia')->after('jumlah_total');
            $table->string('lokasi_barang')->after('jumlah_tersedia');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['deskripsi_barang', 'jumlah_total', 'jumlah_tersedia', 'lokasi_barang']);
        });
    }
};
