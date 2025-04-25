<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('items')) {
            Schema::create('items', function (Blueprint $table) {
                $table->id();
                $table->string('nama_barang');
                $table->string('kode_barang');
                $table->string('merk_barang');
                $table->timestamps();
            });
        } else {
            Schema::table('items', function (Blueprint $table) {
                if (!Schema::hasColumn('items', 'nama_barang')) {
                    $table->string('nama_barang');
                }
                if (!Schema::hasColumn('items', 'kode_barang')) {
                    $table->string('kode_barang');
                }
                if (!Schema::hasColumn('items', 'merk_barang')) {
                    $table->string('merk_barang');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
