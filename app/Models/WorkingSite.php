<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingSite extends Model
{
    use HasFactory;

    protected $fillable = ['site_code', 'site_name', 'location'];

    public function transfersTo()
    {
        return $this->hasMany(Transfer::class, 'to_site_id');
    }

    public function transfersFrom()
    {
        return $this->hasMany(Transfer::class, 'from_site_id');
    }
}
