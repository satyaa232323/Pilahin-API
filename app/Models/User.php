<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'qr_code',
        'qr_image_url',
        'role',
        'points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function wasteDeposits()
    {
        return $this->hasMany(Waste_deposit::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function redeemedVouchers()
    {
        return $this->hasMany(Redeemed_vouchers::class);
    }

    public function managedDropPoints()
    {
        return $this->hasMany(Drop_point::class, 'admin_id');
    }
}
