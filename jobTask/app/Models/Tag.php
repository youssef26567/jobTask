<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Tag extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name'];

    public function posts()
{
    return $this->belongsToMany(Post::class);
}

}
