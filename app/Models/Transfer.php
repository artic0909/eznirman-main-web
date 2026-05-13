<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'machinery_id', 
        'from_site_id', 
        'to_site_id', 
        'transfer_date', 
        'remarks', 
        'status'
    ];

    public function machinery()
    {
        return $this->belongsTo(Machinary::class, 'machinery_id');
    }

    public function fromSite()
    {
        return $this->belongsTo(WorkingSite::class, 'from_site_id');
    }

    public function toSite()
    {
        return $this->belongsTo(WorkingSite::class, 'to_site_id');
    }
}
