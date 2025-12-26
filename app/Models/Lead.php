<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'phone',
        'email',
        'ip_address',
        'user_agent',
        'converted_at',
        'source',
        'notes',
    ];

    protected $casts = [
        'converted_at' => 'datetime',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Scopes
    public function scopeConverted($query)
    {
        return $query->whereNotNull('converted_at');
    }

    public function scopeByProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}

