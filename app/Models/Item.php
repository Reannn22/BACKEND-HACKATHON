<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'merk_barang',
    ];
}
