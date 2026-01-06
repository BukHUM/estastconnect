<?php

namespace App\Console\Commands;

use App\Jobs\ScrapePropertyJob;
use App\Services\ScraperService;
use Illuminate\Console\Command;

class ScrapeProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:run {--url= : Specific URL to scrape}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run property scraper for configured URLs';

    /**
     * Execute the console command.
     */
    public function handle(ScraperService $scraperService)
    {
        $this->info('Starting property scraper...');

        // Get URLs to scrape
        if ($this->option('url')) {
            $urls = [$this->option('url')];
        } else {
            $urls = $scraperService->getUrlsToScrape();
        }

        if (empty($urls)) {
            $this->warn('No URLs configured to scrape. Please add URLs in config/scraper.php');
            return Command::FAILURE;
        }

        $this->info('Found ' . count($urls) . ' URL(s) to scrape');

        // Dispatch jobs for each URL
        $dispatched = 0;
        foreach ($urls as $url) {
            try {
                ScrapePropertyJob::dispatch($url)->onQueue('scraping');
                $dispatched++;
                $this->line("Dispatched job for: {$url}");
                
                // Add delay between jobs to avoid overwhelming the server
                if (count($urls) > 1) {
                    sleep(config('scraper.delay_between_jobs', 2));
                }
            } catch (\Exception $e) {
                $this->error("Failed to dispatch job for {$url}: " . $e->getMessage());
            }
        }

        $this->info("Successfully dispatched {$dispatched} job(s)");
        $this->info('Jobs are being processed in the queue. Check /admin/scraper for results.');

        return Command::SUCCESS;
    }
}
