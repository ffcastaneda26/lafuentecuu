<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'video_url',
        'video_type',
        'title',
    ];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}
