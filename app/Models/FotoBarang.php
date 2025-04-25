<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoBarang extends Model
{
    protected $fillable = ['item_id', 'foto_path'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
