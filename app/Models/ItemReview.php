<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemReview extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'id_item',
        'nama_pengguna',
        'rating',
        'komentar'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }

    public function fotos()
    {
        return $this->hasMany(FotoUlasan::class, 'review_id');
    }
}
