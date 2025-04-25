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
        'gambar_barang'
    ];
}
