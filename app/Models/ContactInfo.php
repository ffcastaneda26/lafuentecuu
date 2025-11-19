<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'social_facebook',
        'social_instagram',
        'social_tiktok',
        'social_twitter',
        'about_text',
        'logo',
    ];
}
