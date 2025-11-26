<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
        'price',
        'image_url'
    ];

    protected $casts = [
        'price' => 'integer',
        'game_id' => 'integer'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
