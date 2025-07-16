<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waste_deposit extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'weight_kg',
        'photo_url',
        'location_id',
        'points_earned',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(related: User::class);
    }

    public function dropPoint()
    {
        return $this->belongsTo(related: Drop_point::class, foreignKey: 'location_id');
    }
}
