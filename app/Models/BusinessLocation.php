<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessLocation extends Model
{
    protected $fillable = [
        'business_profile_id',
        'latitude',
        'longitude',
        'address',
    ];
}
