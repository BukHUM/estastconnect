<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Media;
use App\Models\AffiliateLink;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ScraperService
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('scraper', []);
    }

    /**
     * Scrape properties from a URL
     *
     * @param string $url
     * @return array ['success' => bool, 'properties_found' => int, 'properties_saved' => int, 'error' => string|null]
     */
    public function scrapeUrl(string $url): array
    {
        $startTime = time();
        $propertiesFound = 0;
        $propertiesSaved = 0;
        $error = null;

        try {
            // Fetch HTML content
            $html = $this->fetchHtml($url);
            
            if (empty($html)) {
                return [
                    'success' => false,
                    'properties_found' => 0,
                    'properties_saved' => 0,
                    'error' => 'ไม่สามารถดึงข้อมูล HTML ได้'
                ];
            }

            // Parse properties from HTML
            $properties = $this->parseProperties($html, $url);

            $propertiesFound = count($properties);

            // Save each property
            foreach ($properties as $propertyData) {
                try {
                    $saved = $this->saveProperty($propertyData);
                    if ($saved) {
                        $propertiesSaved++;
                    }
                } catch (\Exception $e) {
                    Log::error('Error saving property', [
                        'url' => $url,
                        'property_data' => $propertyData,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $executionTime = time() - $startTime;

            return [
                'success' => true,
                'properties_found' => $propertiesFound,
                'properties_saved' => $propertiesSaved,
                'execution_time' => $executionTime,
                'error' => null
            ];

        } catch (\Exception $e) {
            Log::error('Scraper Error', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'properties_found' => $propertiesFound,
                'properties_saved' => $propertiesSaved,
                'execution_time' => time() - $startTime,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Fetch HTML content from URL
     */
    protected function fetchHtml(string $url): ?string
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'th-TH,th;q=0.9,en;q=0.8',
                ])
                ->get($url);

            if ($response->successful()) {
                return $response->body();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching HTML', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Parse properties from HTML
     * 
     * NOTE: ต้องปรับแต่งตามโครงสร้าง HTML ของเว็บต้นทาง
     * 
     * ตัวอย่างการใช้งาน:
     * - ใช้ CSS Selectors: $xpath->query("//div[@class='property-card']")
     * - ใช้ XPath: $xpath->query("//div[contains(@class, 'property')]")
     * - Extract text: $node->textContent หรือ $xpath->query(".//h2", $node)->item(0)->textContent
     * - Extract attributes: $node->getAttribute('href')
     */
    protected function parseProperties(string $html, string $baseUrl): array
    {
        $properties = [];
        
        try {
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new \DOMXPath($dom);

            // TODO: ปรับแต่งตามโครงสร้าง HTML ของเว็บต้นทาง
            // ตัวอย่างโครงสร้าง (ต้องแก้ไขให้ตรงกับเว็บจริง):
            
            // วิธีที่ 1: ใช้ CSS Class
            // $propertyNodes = $xpath->query("//div[contains(@class, 'property-card')]");
            
            // วิธีที่ 2: ใช้ XPath ตามโครงสร้าง
            // $propertyNodes = $xpath->query("//article[@class='property']");
            
            // ตัวอย่างการ parse แต่ละ property:
            /*
            foreach ($propertyNodes as $index => $node) {
                $title = $this->extractText($xpath, $node, ".//h2[@class='title']");
                $priceText = $this->extractText($xpath, $node, ".//span[@class='price']");
                $price = $this->parsePrice($priceText);
                $description = $this->extractText($xpath, $node, ".//p[@class='description']");
                $location = $this->extractText($xpath, $node, ".//span[@class='location']");
                
                // Extract images
                $images = [];
                $imageNodes = $xpath->query(".//img[@class='property-image']", $node);
                foreach ($imageNodes as $imgNode) {
                    $imgUrl = $imgNode->getAttribute('src');
                    if ($imgUrl) {
                        $images[] = $this->resolveUrl($imgUrl, $baseUrl);
                    }
                }
                
                // Extract link
                $linkNode = $xpath->query(".//a[@class='property-link']", $node)->item(0);
                $propertyUrl = $linkNode ? $this->resolveUrl($linkNode->getAttribute('href'), $baseUrl) : null;
                
                // Extract external ID from URL or data attribute
                $externalId = $this->extractExternalId($propertyUrl, $node);
                
                if ($title && $price > 0) {
                    $properties[] = [
                        'title' => trim($title),
                        'price' => $price,
                        'description' => trim($description ?? ''),
                        'location' => trim($location ?? ''),
                        'type' => $this->determineType($title, $description),
                        'district' => $this->extractDistrict($location),
                        'province' => $this->extractProvince($location),
                        'images' => $images,
                        'source_url' => $propertyUrl,
                        'external_id' => $externalId,
                        'affiliate_links' => [
                            [
                                'provider' => 'Prop2Share',
                                'url' => $propertyUrl,
                                'is_active' => true,
                            ]
                        ],
                    ];
                }
            }
            */
            
            // สำหรับตอนนี้ return empty array
            // ต้อง implement parsing logic ตามเว็บจริง
            
        } catch (\Exception $e) {
            Log::error('Error parsing properties', [
                'url' => $baseUrl,
                'error' => $e->getMessage()
            ]);
        }
        
        return $properties;
    }

    /**
     * Helper: Extract text from XPath query
     */
    protected function extractText(\DOMXPath $xpath, \DOMNode $context, string $query): ?string
    {
        $nodes = $xpath->query($query, $context);
        if ($nodes->length > 0) {
            return trim($nodes->item(0)->textContent);
        }
        return null;
    }

    /**
     * Helper: Parse price from text
     */
    protected function parsePrice(string $priceText): float
    {
        // Remove non-numeric characters except decimal point
        $cleaned = preg_replace('/[^\d.]/', '', $priceText);
        return (float) $cleaned;
    }

    /**
     * Helper: Resolve relative URL to absolute
     */
    protected function resolveUrl(string $url, string $baseUrl): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        
        $parsed = parse_url($baseUrl);
        $base = $parsed['scheme'] . '://' . $parsed['host'];
        
        if (strpos($url, '/') === 0) {
            return $base . $url;
        }
        
        return $base . '/' . ltrim($url, '/');
    }

    /**
     * Helper: Extract external ID from URL or node
     */
    protected function extractExternalId(?string $url, \DOMNode $node): ?string
    {
        if ($url) {
            // Try to extract ID from URL
            if (preg_match('/\/(\d+)\/?$/', $url, $matches)) {
                return $matches[1];
            }
            if (preg_match('/id[=\/](\d+)/i', $url, $matches)) {
                return $matches[1];
            }
        }
        
        // Try to get from data attribute
        if ($node instanceof \DOMElement) {
            $dataId = $node->getAttribute('data-id');
            if ($dataId) {
                return $dataId;
            }
        }
        
        return null;
    }

    /**
     * Helper: Determine property type from title/description
     */
    protected function determineType(string $title, string $description): string
    {
        $text = strtolower($title . ' ' . $description);
        
        if (preg_match('/\b(คอนโด|condo|condominium|apartment)\b/i', $text)) {
            return 'condo';
        }
        if (preg_match('/\b(บ้าน|house|บ้านเดี่ยว|villa)\b/i', $text)) {
            return 'house';
        }
        if (preg_match('/\b(ที่ดิน|land|plot)\b/i', $text)) {
            return 'land';
        }
        
        return 'condo'; // Default
    }

    /**
     * Helper: Extract district from location
     */
    protected function extractDistrict(string $location): ?string
    {
        // TODO: Implement district extraction logic
        // อาจจะใช้ regex หรือ database lookup
        return null;
    }

    /**
     * Helper: Extract province from location
     */
    protected function extractProvince(string $location): ?string
    {
        // List of Thai provinces
        $provinces = [
            'กรุงเทพ', 'เชียงใหม่', 'ภูเก็ต', 'พัทยา', 'ชลบุรี',
            'นนทบุรี', 'ปทุมธานี', 'สมุทรปราการ', 'นครปฐม',
            // เพิ่มจังหวัดอื่นๆ ตามต้องการ
        ];
        
        foreach ($provinces as $province) {
            if (strpos($location, $province) !== false) {
                return $province;
            }
        }
        
        return null;
    }

    /**
     * Save property to database
     */
    protected function saveProperty(array $data): bool
    {
        try {
            // Check if property already exists
            $existing = Property::where('external_id', $data['external_id'] ?? null)
                ->orWhere('source_url', $data['source_url'] ?? null)
                ->first();

            if ($existing) {
                // Update existing property
                $existing->update([
                    'title' => $data['title'] ?? $existing->title,
                    'description' => $data['description'] ?? $existing->description,
                    'price' => $data['price'] ?? $existing->price,
                    'type' => $data['type'] ?? $existing->type,
                    'location' => $data['location'] ?? $existing->location,
                    'district' => $data['district'] ?? null,
                    'province' => $data['province'] ?? null,
                    'source_url' => $data['source_url'] ?? $existing->source_url,
                ]);

                $property = $existing;
            } else {
                // Create new property
                $property = Property::create([
                    'title' => $data['title'] ?? 'Untitled',
                    'slug' => $this->generateSlug($data['title'] ?? 'untitled'),
                    'description' => $data['description'] ?? '',
                    'price' => $data['price'] ?? 0,
                    'type' => $data['type'] ?? 'condo',
                    'location' => $data['location'] ?? '',
                    'district' => $data['district'] ?? null,
                    'province' => $data['province'] ?? null,
                    'status' => 'pending',
                    'source_url' => $data['source_url'] ?? null,
                    'external_id' => $data['external_id'] ?? null,
                ]);
            }

            // Save images
            if (isset($data['images']) && is_array($data['images'])) {
                $this->saveImages($property, $data['images']);
            }

            // Save affiliate links
            if (isset($data['affiliate_links']) && is_array($data['affiliate_links'])) {
                $this->saveAffiliateLinks($property, $data['affiliate_links']);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error saving property', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Save images for property
     */
    protected function saveImages(Property $property, array $imageUrls): void
    {
        foreach ($imageUrls as $index => $imageUrl) {
            try {
                // Download image
                /** @var \Illuminate\Http\Client\Response $imageContent */
                $imageContent = Http::timeout(30)->get($imageUrl);
                
                if ($imageContent->successful()) {
                    $extension = $this->getImageExtension($imageUrl, $imageContent->header('Content-Type'));
                    $filename = 'properties/' . $property->id . '/' . Str::random(10) . '.' . $extension;
                    $imageBody = $imageContent->body();
                    
                    Storage::disk('public')->put($filename, $imageBody);
                    
                    Media::updateOrCreate(
                        [
                            'property_id' => $property->id,
                            'image_url' => $imageUrl,
                        ],
                        [
                            'local_path' => $filename,
                            'is_main' => $index === 0,
                            'order' => $index,
                            'file_size' => strlen($imageBody),
                            'mime_type' => $imageContent->header('Content-Type') ?? 'image/jpeg',
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::warning('Error downloading image', [
                    'property_id' => $property->id,
                    'image_url' => $imageUrl,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Save affiliate links for property
     */
    protected function saveAffiliateLinks(Property $property, array $links): void
    {
        foreach ($links as $linkData) {
            AffiliateLink::updateOrCreate(
                [
                    'property_id' => $property->id,
                    'provider' => $linkData['provider'] ?? 'unknown',
                ],
                [
                    'link_url' => $linkData['url'] ?? null,
                    'is_active' => $linkData['is_active'] ?? true,
                ]
            );
        }
    }

    /**
     * Generate slug from title
     */
    protected function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        
        // Ensure uniqueness
        $count = Property::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        
        return $slug;
    }

    /**
     * Get image extension from URL or Content-Type
     */
    protected function getImageExtension(string $url, ?string $contentType): string
    {
        // Try to get from URL
        $path = parse_url($url, PHP_URL_PATH);
        if ($path && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path, $matches)) {
            return strtolower($matches[1]);
        }

        // Try to get from Content-Type
        if ($contentType) {
            $mimeToExt = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
            ];
            
            if (isset($mimeToExt[$contentType])) {
                return $mimeToExt[$contentType];
            }
        }

        return 'jpg'; // Default
    }

    /**
     * Get URLs to scrape from config
     */
    public function getUrlsToScrape(): array
    {
        return $this->config['urls'] ?? [];
    }
}

