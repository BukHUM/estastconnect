<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'image_url',
        'local_path',
        'is_main',
        'order',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'order' => 'integer',
        'file_size' => 'integer',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Scopes
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

