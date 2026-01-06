<?php

namespace App\Jobs;

use App\Services\ScraperService;
use App\Models\ScraperLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScrapePropertyJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $backoff = 60; // Wait 60 seconds before retry
    public $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $url
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ScraperService $scraperService): void
    {
        $startTime = time();

        try {
            // Scrape the URL
            $result = $scraperService->scrapeUrl($this->url);

            // Determine status
            $status = 'success';
            if (!$result['success']) {
                $status = 'failed';
            } elseif ($result['properties_saved'] < $result['properties_found']) {
                $status = 'partial';
            }

            // Create or update scraper log
            ScraperLog::updateOrCreate(
                ['url' => $this->url],
                [
                    'status' => $status,
                    'error_message' => $result['error'],
                    'properties_found' => $result['properties_found'],
                    'properties_saved' => $result['properties_saved'],
                    'execution_time' => $result['execution_time'] ?? (time() - $startTime),
                    'last_scraped_at' => now(),
                    'next_scrape_at' => now()->addHours(config('scraper.interval_hours', 24)),
                ]
            );

            Log::info('Scraping completed', [
                'url' => $this->url,
                'status' => $status,
                'properties_found' => $result['properties_found'],
                'properties_saved' => $result['properties_saved'],
            ]);

        } catch (\Exception $e) {
            // Log error
            ScraperLog::updateOrCreate(
                ['url' => $this->url],
                [
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'properties_found' => 0,
                    'properties_saved' => 0,
                    'execution_time' => time() - $startTime,
                    'last_scraped_at' => now(),
                ]
            );

            Log::error('Scraping failed', [
                'url' => $this->url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        ScraperLog::updateOrCreate(
            ['url' => $this->url],
            [
                'status' => 'failed',
                'error_message' => 'Job failed after ' . $this->tries . ' attempts: ' . $exception->getMessage(),
                'last_scraped_at' => now(),
            ]
        );

        Log::error('Scraping job failed permanently', [
            'url' => $this->url,
            'error' => $exception->getMessage()
        ]);
    }
}
