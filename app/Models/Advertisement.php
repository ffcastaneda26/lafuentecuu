<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'sponsor_id',
        'title',
        'ad_type',
        'content',
        'position',
        'click_url',
        'impressions_count',
        'clicks_count',
        'status',
        'start_date',
        'end_date',
        'priority',
    ];

    protected $casts = [
        'content' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'impressions_count' => 'integer',
        'clicks_count' => 'integer',
        'priority' => 'integer',
    ];

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position)
            ->orderBy('priority', 'desc');
    }

    public function incrementImpressions()
    {
        $this->increment('impressions_count');
    }

    public function incrementClicks()
    {
        $this->increment('clicks_count');
    }
}
