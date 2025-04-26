<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['nama_lokasi'];
    protected $visible = ['id', 'nama_lokasi'];
}
