<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class UserLogin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token', // Add this if not already present
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
 protected $except = [
        'api/register',
        'api/login',
        'api/logout',
        'api/remember-token',
        'api/refresh-token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
