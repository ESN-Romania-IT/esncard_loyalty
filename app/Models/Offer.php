<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Offer types: 'discount' | 'stamp_card'

class Offer extends Model
{
    protected $fillable = [
        'business_profile_id',
        'title',
        'type',
        'uses_per_client',
        'stamps_required',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function business_rofiles(){
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }

    public function redemptions(){
        return $this->hasMany(OfferRedemption::class);
    }


    public function isStampCard(): bool
    {
        return $this->type === 'stamp_card';
    }
}
