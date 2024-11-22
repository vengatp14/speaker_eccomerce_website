<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
    protected $fillable = [
        'mobile',
        'otp',
        'expires_at',
        'name',
        'pan_number',
        'aadhar_number',
        'license_number',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'expires_at' => 'datetime',
    ];
}
