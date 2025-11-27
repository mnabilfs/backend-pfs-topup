<?php
// app/Models/Game.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'publisher',
        'image_url',
        'banner_url'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'game_id');
    }
}
