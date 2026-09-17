<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'date',
        'accountcode_id',
        'amount',
        'note',
        'type',
        'balance_after',
        'pay_to',
        'pay_to_code',
        'site_id',
        'approval',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'datetime',
        'approval' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function accountcode()
    {
        return $this->belongsTo(Accountcode::class);
    }

    public function site()
    {
        return $this->belongsTo(WorkingSite::class, 'site_id');
    }

    public function isHeadOffice(): bool
    {
        if ($this->relationLoaded('site') && $this->site) {
            return $this->site->isHeadOffice();
        }
        if ($this->site_id) {
            $site = $this->site ?? WorkingSite::find($this->site_id);
            if ($site && $site->isHeadOffice()) {
                return true;
            }
        }
        if ($this->relationLoaded('wallet') && $this->wallet && $this->wallet->relationLoaded('user') && $this->wallet->user) {
            return $this->wallet->user->isHeadOfficeAssigned();
        }
        if ($this->wallet && $this->wallet->user) {
            return $this->wallet->user->isHeadOfficeAssigned();
        }
        return false;
    }

    public function getIsApprovedAttribute(): bool
    {
        if ($this->approval == 1 || $this->type === 'credit' || !empty($this->approved_at)) {
            return true;
        }
        return $this->isHeadOffice();
    }
}
