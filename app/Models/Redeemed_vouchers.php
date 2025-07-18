<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redeemed_vouchers extends Model
{
    protected $table = 'redeemed_vouchers'; // kalau nama tabel plural seperti ini

    protected $fillable = [
        'user_id',
        'voucher_id',
        'code',
        'redeemed_at',
        'status',
    ];

        public $timestamps = false; // kalau tabel tidak punya kolom created_at & updated_at


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
