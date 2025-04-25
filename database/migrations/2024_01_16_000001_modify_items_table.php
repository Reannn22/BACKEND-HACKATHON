<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                // Backup existing columns if needed
                if (Schema::hasColumn('items', 'nama_item')) {
                    $table->string('nama_barang')->after('id')->nullable();
                    DB::statement('UPDATE items SET nama_barang = nama_item');
                    $table->dropColumn('nama_item');
                }

                // Add new columns if they don't exist
                if (!Schema::hasColumn('items', 'kode_barang')) {
                    $table->string('kode_barang')->after('nama_barang')->nullable();
                }
                if (!Schema::hasColumn('items', 'merk_barang')) {
                    $table->string('merk_barang')->after('kode_barang')->nullable();
                }
            });
        } else {
            Schema::create('items', function (Blueprint $table) {
                $table->id();
                $table->string('nama_barang');
                $table->string('kode_barang');
                $table->string('merk_barang');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (Schema::hasColumn('items', 'nama_barang')) {
                    $table->renameColumn('nama_barang', 'nama_item');
                }
                if (Schema::hasColumn('items', 'kode_barang')) {
                    $table->dropColumn('kode_barang');
                }
                if (Schema::hasColumn('items', 'merk_barang')) {
                    $table->dropColumn('merk_barang');
                }
            });
        }
    }
};
