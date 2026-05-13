<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialConsume extends Model
{
    protected $fillable = [
        'material_purchase_id', 
        'consume_date', 
        'quantity_current', 
        'used_quantity', 
        'available_quantity', 
        'unit', 
        'use_now', 
        'from_site_id', 
        'to_site_id', 
        'note'
    ];

    public function purchase()
    {
        return $this->belongsTo(MaterialPurchase::class, 'material_purchase_id');
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
