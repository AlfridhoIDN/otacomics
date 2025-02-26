<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MangaPage extends Model
{
    use HasFactory;

    protected $fillable = ['comic_id', 'file_path'];

    public function comic()
    {
        return $this->belongsTo(Comic::class);
    }
}



