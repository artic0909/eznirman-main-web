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
        'approval',
        'approved_at',
        'invoice_file',
        'note',
    ];

    protected $casts = [
        'approval' => 'boolean',
        'approved_at' => 'datetime',
        'purchase_date' => 'date',
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

    public function isHeadOffice(): bool
    {
        if ($this->relationLoaded('site') && $this->site) {
            return $this->site->isHeadOffice();
        }
        if ($this->working_site_id) {
            $site = $this->site ?? WorkingSite::find($this->working_site_id);
            if ($site && $site->isHeadOffice()) {
                return true;
            }
        }
        if ($this->relationLoaded('user') && $this->user) {
            return $this->user->isHeadOfficeAssigned();
        }
        if ($this->user_id) {
            $user = $this->user ?? User::find($this->user_id);
            return $user ? $user->isHeadOfficeAssigned() : false;
        }
        return false;
    }

    public function getIsApprovedAttribute(): bool
    {
        if ($this->approval == 1 || !empty($this->approved_at)) {
            return true;
        }
        return $this->isHeadOffice();
    }
}
