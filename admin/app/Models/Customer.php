<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'mobile',
        'pan_number',
        'aadhar_number',
        'license_number',
    ];
}
