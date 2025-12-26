<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScraperLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'status',
        'error_message',
        'properties_found',
        'properties_saved',
        'execution_time',
        'last_scraped_at',
        'next_scrape_at',
    ];

    protected $casts = [
        'properties_found' => 'integer',
        'properties_saved' => 'integer',
        'execution_time' => 'integer',
        'last_scraped_at' => 'datetime',
        'next_scrape_at' => 'datetime',
    ];

    // Scopes
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('last_scraped_at', '>=', now()->subDays($days));
    }
}

