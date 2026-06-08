<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientStamp extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'client_profile_id',
        'business_profile_id',
        'awarded_at',
        'redeemed_at',
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    public function clientProfile()
    {
        return $this->belongsTo(ClientProfile::class, 'client_profile_id');
    }

    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }
}
