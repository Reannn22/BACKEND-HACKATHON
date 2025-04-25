<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemReview extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'id_item',
        'rating',
        'komentar'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
