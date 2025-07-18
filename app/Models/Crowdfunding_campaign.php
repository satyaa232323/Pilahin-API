<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crowdfunding_campaign extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'current_amount',
        'photo_url',
        'location_id',
        'status',
    ];

    protected $casts = [
        'end_date' => 'datetime',
    ];

    // Relationships
    public function dropPoint()
    {
        return $this->belongsTo(Drop_point::class, 'location_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'campaign_id');
    }

    public function location()
    {
        return $this->belongsTo(Drop_point::class, 'location_id');
    }
}