<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Lead;
use App\Models\ScraperLog;
use App\Models\AffiliateLink;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_properties' => Property::pending()->count(),
            'published_properties' => Property::published()->count(),
            'failed_scrapes' => ScraperLog::failed()->recent(7)->count(),
            'total_clicks' => AffiliateLink::sum('click_count'),
            'total_leads' => Lead::count(),
            'recent_leads' => Lead::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $recentProperties = Property::with('mainImage')
            ->latest()
            ->limit(5)
            ->get();

        $recentLeads = Lead::with('property')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProperties', 'recentLeads'));
    }
}

