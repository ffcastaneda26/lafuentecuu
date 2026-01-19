<?php

namespace App\Models;

use App\Enums\AdvertisementPositionEnum;
use App\Enums\AdvertisementTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Advertisement extends Model
{
    /** @use HasFactory<\Database\Factories\AdvertisementFactory> */
    use HasFactory;
    protected $fillable = [
        'sponsor_id',
        'title',
        'type',
        'description',
        'position',
        'click_url',
        'media_url',
        'active',
        'start_date',
        'end_date',
        'priority',
        'clicks_count',
    ];


    protected $casts = [
        'active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'ad_type' => AdvertisementTypeEnum::class,
        'position' => AdvertisementPositionEnum::class,
        'priority' => 'integer',
    ];


    protected static function booted()
    {
        static::deleted(function ($model) {
            if ($model->media_url) {
                Storage::disk('public')->delete($model->media_url);
            }
        });
    }
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now()->startOfDay());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->endOfDay());
            });
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position)
            ->orderBy('priority', 'desc');
    }


    public function incrementClicks()
    {
        $this->increment('clicks_count');
    }
}
