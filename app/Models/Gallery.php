<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // Define which attributes are mass assignable
    protected $fillable = [
        'title',
        'description',
        'image',
    ];

    // Define any relationships if applicable (e.g., belongsTo, hasMany)
    // For this example, we don't have relationships, but you can add them here if needed

    // Add any other model-specific methods or scopes here
}
