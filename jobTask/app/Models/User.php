<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasFactory, Notifiable , HasApiTokens;

    protected $fillable = [
        'name',
        'phone',
        'password',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(related: Post::class);
    }
    protected static function booted()
{
    static::created(fn () => Cache::forget('stats'));
    static::updated(fn () => Cache::forget('stats'));
    static::deleted(fn () => Cache::forget('stats'));
}


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
