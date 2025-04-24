<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activities_log';
    protected $fillable = ['nama_activity'];
}
