<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Scraper Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the property scraper system
    |
    */

    /*
    |--------------------------------------------------------------------------
    | URLs to Scrape
    |--------------------------------------------------------------------------
    |
    | List of URLs that the scraper should process
    | Add your target URLs here
    |
    */
    'urls' => [
        // Example URLs (replace with actual URLs)
        // 'https://example.com/properties',
        // 'https://example.com/condos',
        // 'https://example.com/houses',
    ],

    /*
    |--------------------------------------------------------------------------
    | Scraping Interval
    |--------------------------------------------------------------------------
    |
    | How often to scrape each URL (in hours)
    |
    */
    'interval_hours' => env('SCRAPER_INTERVAL_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | Delay Between Jobs
    |--------------------------------------------------------------------------
    |
    | Delay in seconds between dispatching scraping jobs
    | This helps avoid overwhelming the target server
    |
    */
    'delay_between_jobs' => env('SCRAPER_DELAY_BETWEEN_JOBS', 2),

    /*
    |--------------------------------------------------------------------------
    | Request Settings
    |--------------------------------------------------------------------------
    |
    | HTTP request settings for scraping
    |
    */
    'request' => [
        'timeout' => env('SCRAPER_TIMEOUT', 30),
        'user_agent' => env('SCRAPER_USER_AGENT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
        'headers' => [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'th-TH,th;q=0.9,en;q=0.8',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Settings
    |--------------------------------------------------------------------------
    |
    | Queue configuration for scraping jobs
    |
    */
    'queue' => [
        'name' => env('SCRAPER_QUEUE', 'scraping'),
        'connection' => env('SCRAPER_QUEUE_CONNECTION', 'default'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Parsing Settings
    |--------------------------------------------------------------------------
    |
    | Settings for parsing HTML content
    | These selectors need to be customized based on the target website structure
    |
    */
    'parsing' => [
        // CSS selectors or XPath expressions for finding properties
        'property_container' => null, // e.g., '.property-card' or '//div[@class="property"]'
        'title_selector' => null,
        'price_selector' => null,
        'description_selector' => null,
        'location_selector' => null,
        'image_selector' => null,
        'link_selector' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Settings
    |--------------------------------------------------------------------------
    |
    | Settings for downloading and storing images
    |
    */
    'images' => [
        'download' => env('SCRAPER_DOWNLOAD_IMAGES', true),
        'max_size' => env('SCRAPER_MAX_IMAGE_SIZE', 5242880), // 5MB in bytes
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'storage_disk' => env('SCRAPER_IMAGE_DISK', 'public'),
        'storage_path' => 'properties',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Property Values
    |--------------------------------------------------------------------------
    |
    | Default values for properties when certain fields are missing
    |
    */
    'defaults' => [
        'type' => 'condo',
        'status' => 'pending',
        'province' => null,
        'district' => null,
    ],
];

