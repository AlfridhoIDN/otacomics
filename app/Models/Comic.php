<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Review;

use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    protected $table = 'comics'; // Sesuaikan dengan tabel yang ada di database
    protected $fillable = ['title', 'description', 'author', 'genre', 'release_date', 'status'];


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mangas()
{
    return $this->hasMany(Manga::class);
}
}

