<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnauthorizedPurchase extends Model
{
    protected $fillable = [
        'unauthorized_unique_id',
        'working_site_id',
        'user_id',
        'purchase_date',
        'product_name',
        'amount',
        'invoice_file',
        'note',
    ];

    protected static function booted()
    {
        static::creating(function ($purchase) {
            $lastPurchase = UnauthorizedPurchase::latest('id')->first();
            $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;
            $purchase->unauthorized_unique_id = 'UPRCH-' . str_pad($nextId, 2, '0', STR_PAD_LEFT);
            
            if (auth()->check()) {
                $purchase->user_id = auth()->id();
            }
        });
    }

    public function site()
    {
        return $this->belongsTo(WorkingSite::class, 'working_site_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
