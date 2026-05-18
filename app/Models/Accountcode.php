<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accountcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    protected static function booted()
    {
        static::creating(function ($accountcode) {
            // Retrieve the last record's ID to generate the sequential code
            $lastRecord = self::latest('id')->first();
            $nextId = $lastRecord ? ($lastRecord->id + 1) : 1;
            
            // Auto generate code: e.g., 01, 02... 010...
            $accountcode->code = '0' . $nextId;
        });
    }
}
