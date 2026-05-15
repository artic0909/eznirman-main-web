<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialPurchase extends Model
{
    protected $fillable = [
        'material_unique_id',
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
        'note',
        'created_by',
        'type'
    ];

    protected static function booted()
    {
        static::creating(function ($purchase) {
            $lastPurchase = MaterialPurchase::latest('id')->first();
            $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;
            $purchase->material_unique_id = 'PRCH-' . str_pad($nextId, 2, '0', STR_PAD_LEFT);

            if (auth()->guard('admin')->check()) {
                $purchase->created_by = auth()->guard('admin')->id();
                $purchase->type = 'admin';
            } elseif (auth()->check()) {
                $purchase->created_by = auth()->id();
                $purchase->type = 'user';
            }
        });
    }

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
