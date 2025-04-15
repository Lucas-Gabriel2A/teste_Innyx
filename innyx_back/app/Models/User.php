<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

   
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // JWT: claims personalizados
    public function getJWTCustomClaims()
    {
        return [];
    }

    
    public function getAuthIdentifierName()
    {
        return 'username';
    }
}
