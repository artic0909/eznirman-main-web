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
    ];

    protected $casts = [
        'date' => 'datetime',
        'approval' => 'boolean',
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
}
