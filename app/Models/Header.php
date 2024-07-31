<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_url',
        'logo_alt_text',
        'navigation_menu',
        'search_bar',
    ];

    protected $casts = [
        'navigation_menu' => 'array',
        'search_bar' => 'array',
    ];
}

