<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'merek_barang',
        'tahun_pengadaan',
        'deskripsi_barang',
        'jumlah_barang',
        'jumlah_tersedia',
        'lokasi_barang',
        'id_kategori',
        'is_dibawa',
        'berat_barang',
        'foto_barang'  // Changed from gambar_barang
    ];

    protected $hidden = ['gambar_barang']; // Hide old column name

    protected $with = ['foto_barang']; // Add this line to eager load photos

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }

    public function foto_barang()
    {
        return $this->hasMany(FotoBarang::class, 'item_id');
    }
}
