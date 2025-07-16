<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Waste_deposit;

class Drop_point extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'photo_url',
        'admin_id',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function wasteDeposits()
    {
        return $this->hasMany(related: Waste_deposit::class, foreignKey: 'location_id');
    }

    public function crowdfundingCampaigns()
    {
        return $this->hasMany(related: Crowdfunding_campaign::class, foreignKey: 'location_id');
    }
}