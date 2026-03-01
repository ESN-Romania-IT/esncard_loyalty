<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offers() {
        return $this->hasMany(Offer::class, 'business_profile_id');
    }

    public function redemptions(){
        return $this->hasMany(OfferRedemption::class, 'business_profile_id');
    }
}
