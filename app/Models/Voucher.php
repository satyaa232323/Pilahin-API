<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'title',
        'points_required',
        'status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function redeemedVouchers()
    {
        return $this->hasMany(Redeemed_vouchers::class, 'voucher_id');
    }
}
