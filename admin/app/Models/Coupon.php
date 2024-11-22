<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_amount',
        'max_uses',
        'expiry_date',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expiry_date' => 'date',
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2'
    ];

    public function isValid()
    {
        return $this->is_active &&
               ($this->expiry_date === null || $this->expiry_date->isFuture());
    }
}
