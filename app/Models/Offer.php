<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'business_profile_id',
        'title',
        'uses_per_client',
        'is_active'
    ];

    public function businessProfiles(){
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }

    public function redemptions(){
        return $this->hasMany(OfferRedemption::class);
    }
}
