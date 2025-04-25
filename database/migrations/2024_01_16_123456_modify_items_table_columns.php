<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('nama_item', 'nama_barang');
            $table->string('kode_barang')->after('nama_barang');
            $table->string('merk_barang')->after('kode_barang');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('nama_barang', 'nama_item');
            $table->dropColumn(['kode_barang', 'merk_barang']);
        });
    }
};
