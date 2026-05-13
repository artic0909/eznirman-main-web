<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialPurchase extends Model
{
    protected $fillable = [
        'working_site_id', 
        'purchase_date', 
        'material_code_id', 
        'product_name', 
        'party_name', 
        'invoice_no', 
        'quantity', 
        'unit_id', 
        'rate', 
        'gst_amount', 
        'amount', 
        'invoice_file', 
        'note'
    ];

    public function site()
    {
        return $this->belongsTo(WorkingSite::class, 'working_site_id');
    }

    public function materialCode()
    {
        return $this->belongsTo(MaterialCode::class, 'material_code_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function consumes()
    {
        return $this->hasMany(MaterialConsume::class);
    }
}
