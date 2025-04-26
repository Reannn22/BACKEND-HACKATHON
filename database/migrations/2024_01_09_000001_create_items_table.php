<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('kode_barang')->unique();
            $table->string('merek_barang');
            $table->integer('tahun_pengadaan');
            $table->text('deskripsi_barang');
            $table->integer('jumlah_barang');
            $table->integer('jumlah_tersedia');
            $table->foreignId('id_kategori')->constrained('categories');
            $table->foreignId('id_lokasi')->constrained('locations');
            $table->foreignId('id_admin')->constrained('users');
            $table->boolean('is_dibawa');
            $table->decimal('berat_barang', 10, 2);
            $table->string('warna_barang');
            $table->enum('kondisi_barang', ['baru', 'baik', 'rusak ringan', 'rusak berat']);
            $table->enum('status_barang', ['aktif', 'non-aktif', 'dipinjam', 'dalam perbaikan']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
