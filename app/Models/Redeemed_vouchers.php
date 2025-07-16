<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redeemed_vouchers extends Model
{
    protected $fillable = [
        'user_id',
        'voucher_id',
        'code',
        'redeemed_at',
        'status',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
