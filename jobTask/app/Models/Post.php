<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use App\Models\User;
use App\Models\Tag;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'body', 'cover_image', 'pinned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
{
    static::created(fn () => Cache::forget('stats'));
    static::updated(fn () => Cache::forget('stats'));
    static::deleted(fn () => Cache::forget('stats'));
}

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
