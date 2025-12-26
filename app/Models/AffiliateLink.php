<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'provider',
        'link_url',
        'click_count',
        'last_clicked_at',
        'is_active',
    ];

    protected $casts = [
        'click_count' => 'integer',
        'is_active' => 'boolean',
        'last_clicked_at' => 'datetime',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

