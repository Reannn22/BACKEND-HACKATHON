<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoUlasan extends Model
{
    protected $fillable = ['review_id', 'foto_path'];

    public function review()
    {
        return $this->belongsTo(ItemReview::class, 'review_id');
    }
}
