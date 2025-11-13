<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'contact_person',
        'logo',
        'website',
        'status',
        'contract_start',
        'contract_end',
    ];

    protected $casts = [
        'contract_start' => 'date',
        'contract_end' => 'date',
    ];

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
