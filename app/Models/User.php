<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nome',
        'email',
        'password',
        'status',
        'quantiaProjetos',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // inclui "nome" no JSON da API
    protected $appends = ['nome'];

    public function getNomeAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    public function setNomeAttribute($value)
    {
        $this->attributes['name'] = $value;
    }
}