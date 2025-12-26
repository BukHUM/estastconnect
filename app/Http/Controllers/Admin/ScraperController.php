<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScraperLog;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    public function index(Request $request)
    {
        $query = ScraperLog::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('last_scraped_at', [
                $request->date_from,
                $request->date_to
            ]);
        }

        $logs = $query->latest('last_scraped_at')->paginate(20);

        // Statistics
        $stats = [
            'total_runs' => ScraperLog::count(),
            'success_rate' => ScraperLog::count() > 0 
                ? round((ScraperLog::success()->count() / ScraperLog::count()) * 100, 2)
                : 0,
            'error_rate' => ScraperLog::count() > 0
                ? round((ScraperLog::failed()->count() / ScraperLog::count()) * 100, 2)
                : 0,
            'recent_success' => ScraperLog::success()->recent(7)->count(),
            'recent_failed' => ScraperLog::failed()->recent(7)->count(),
        ];

        return view('admin.scraper.index', compact('logs', 'stats'));
    }

    public function run()
    {
        // TODO: Dispatch scraping job
        return back()->with('success', 'เริ่มต้นการ Scrape เรียบร้อยแล้ว');
    }
}

