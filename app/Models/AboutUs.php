<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    // Add the $fillable property to specify which attributes can be mass assigned
    protected $fillable = [
        'title',
        'description',
        'image',
        'contact_email',
        'contact_phone',
    ];
}
