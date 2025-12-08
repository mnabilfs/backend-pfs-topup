<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundMusic extends Model
{
    use HasFactory;

    // 🔥 TAMBAHKAN BARIS INI
    protected $table = 'background_musics';

    protected $fillable = [
        'title',
        'artist',
        'audio_url',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Scope untuk mendapatkan musik aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}