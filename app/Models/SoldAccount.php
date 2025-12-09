<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // BARIS INI WAJIB DITAMBAH
use Illuminate\Database\Eloquent\Model;

class SoldAccount extends Model
{
    use HasFactory; // ini sekarang jadi hijau

    protected $fillable = [
        'title',
        'description',
        'price',
        'image_url',
        'gallery',
        'is_active',
        'order'
    ];

    protected $casts = [
        'gallery'    => 'array',
        'is_active'  => 'boolean',
    ];
}
