<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_sites_ids',
    ];

    protected $casts = [
        'assigned_sites_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
