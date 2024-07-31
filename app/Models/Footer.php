<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_information',
        'quick_links',
        'social_media_links',
        'newsletter_signup',
    ];
}
