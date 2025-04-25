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
        'gambar_barang',
        'deskripsi_barang',
        'jumlah_barang',
        'jumlah_tersedia',
        'lokasi_barang',
        'id_kategori',
        'is_dibawa'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }
}
