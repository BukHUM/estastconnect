<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'ai_description',
        'price',
        'type',
        'location',
        'district',
        'province',
        'status',
        'source_url',
        'external_id',
        'view_count',
        'click_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'view_count' => 'integer',
        'click_count' => 'integer',
    ];

    // Relationships
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function affiliateLinks()
    {
        return $this->hasMany(AffiliateLink::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function mainImage()
    {
        return $this->hasOne(Media::class)->where('is_main', true);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }
}

