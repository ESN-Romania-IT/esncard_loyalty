<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferRedemption extends Model
{
    protected $fillable = [
        'offer_id',
        'business_profile_id',
        'client_profile_id',
        'redeemed_at'
    ];

    public function offer(){
        return $this->belongsTo(Offer::class);
    }

    public function clientProfile(){
        return $this->belongsTo(ClientProfile::class, 'client_profile_id');
    }

    public function business_profile(){
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }
}
