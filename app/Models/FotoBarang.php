<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoBarang extends Model
{
    protected $table = 'foto_barang';

    protected $fillable = [
        'item_id',
        'foto_path'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
