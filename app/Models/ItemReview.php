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

    protected $with = ['fotos']; // Eager load fotos by default

    public function fotos()
    {
        return $this->hasMany(FotoUlasan::class, 'review_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
