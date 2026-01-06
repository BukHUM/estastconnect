<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScraperLog;
use App\Jobs\ScrapePropertyJob;
use App\Services\ScraperService;
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

    public function run(Request $request, ScraperService $scraperService)
    {
        $url = $request->input('url');
        
        if ($url) {
            // Scrape specific URL
            ScrapePropertyJob::dispatch($url)->onQueue('scraping');
            
            return back()->with('success', "เริ่มต้นการ Scrape URL: {$url} เรียบร้อยแล้ว");
        } else {
            // Scrape all configured URLs
            $urls = $scraperService->getUrlsToScrape();
            
            if (empty($urls)) {
                return back()->with('error', 'ไม่มี URL ที่ตั้งค่าไว้ กรุณาเพิ่ม URL ใน config/scraper.php');
            }
            
            $dispatched = 0;
            foreach ($urls as $urlToScrape) {
                ScrapePropertyJob::dispatch($urlToScrape)->onQueue('scraping');
                $dispatched++;
            }
            
            return back()->with('success', "เริ่มต้นการ Scrape {$dispatched} URL(s) เรียบร้อยแล้ว");
        }
    }
}

