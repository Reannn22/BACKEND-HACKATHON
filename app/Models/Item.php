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
        'nama_kategori',
        'id_kategori',
        'is_dibawa',
        'berat_barang'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }

    public function fotos()
    {
        return $this->hasMany(FotoBarang::class);
    }
}
