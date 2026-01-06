# 🔄 Scraper System - คู่มือการตั้งค่าและใช้งาน

## ✅ สิ่งที่ทำเสร็จแล้ว

1. ✅ สร้าง `ScraperService` สำหรับจัดการ logic การ scrape
2. ✅ สร้าง `ScrapePropertyJob` สำหรับ Queue Job
3. ✅ สร้าง `ScrapeProperties` Command สำหรับรัน Scraper
4. ✅ แก้ไข `ScraperController` ให้ dispatch job จริง
5. ✅ สร้าง `config/scraper.php` สำหรับตั้งค่า
6. ✅ ตั้งค่า Scheduled Task ใน `routes/console.php`

## 🔧 การตั้งค่า

### 1. ตั้งค่า Queue

ในไฟล์ `.env`:

```env
QUEUE_CONNECTION=database
# หรือใช้ Redis (แนะนำ)
# QUEUE_CONNECTION=redis
```

### 2. สร้าง Queue Table (ถ้าใช้ Database Queue)

```bash
php artisan queue:table
php artisan migrate
```

### 3. ตั้งค่า URLs ที่จะ Scrape

แก้ไขไฟล์ `config/scraper.php`:

```php
'urls' => [
    'https://example.com/properties',
    'https://example.com/condos',
    'https://example.com/houses',
],
```

### 4. ตั้งค่า Environment Variables (Optional)

```env
SCRAPER_INTERVAL_HOURS=24
SCRAPER_DELAY_BETWEEN_JOBS=2
SCRAPER_TIMEOUT=30
SCRAPER_DOWNLOAD_IMAGES=true
SCRAPER_MAX_IMAGE_SIZE=5242880
```

## 🚀 วิธีใช้งาน

### วิธีที่ 1: รันผ่าน Admin Panel

1. ไปที่ `/admin/scraper`
2. คลิกปุ่ม "รัน Scraper ทันที"
3. ระบบจะ dispatch jobs ไปยัง Queue
4. ดูผลลัพธ์ในตาราง Scraping Logs

### วิธีที่ 2: รันผ่าน Command Line

#### รัน Scraper ทั้งหมด
```bash
php artisan scraper:run
```

#### รัน Scraper URL เฉพาะ
```bash
php artisan scraper:run --url=https://example.com/properties
```

### วิธีที่ 3: Scheduled (อัตโนมัติ)

ระบบจะรัน Scraper อัตโนมัติทุกวันเวลา 02:00 น.

**สำหรับ Development:**
```bash
php artisan schedule:work
```

**สำหรับ Production:**
เพิ่มใน Crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🔄 Queue Worker

### รัน Queue Worker

```bash
# Development
php artisan queue:work --queue=scraping,default

# Production (with supervisor)
php artisan queue:work --queue=scraping,default --tries=3 --timeout=300
```

### ตรวจสอบ Queue Status

```bash
php artisan queue:monitor
```

## 📝 การปรับแต่ง ScraperService

### ปรับแต่ง Parsing Logic

ไฟล์ `app/Services/ScraperService.php` มี method `parseProperties()` ที่ต้องปรับแต่งตามโครงสร้าง HTML ของเว็บต้นทาง

**ตัวอย่างการใช้งาน DOMDocument:**

```php
protected function parseProperties(string $html, string $baseUrl): array
{
    $properties = [];
    $dom = new \DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new \DOMXPath($dom);

    // หา property cards (ปรับตามโครงสร้างจริง)
    $propertyNodes = $xpath->query("//div[@class='property-card']");
    
    foreach ($propertyNodes as $node) {
        $properties[] = [
            'title' => $this->extractText($xpath, $node, ".//h2[@class='title']"),
            'price' => $this->extractPrice($xpath, $node, ".//span[@class='price']"),
            'description' => $this->extractText($xpath, $node, ".//p[@class='description']"),
            'location' => $this->extractText($xpath, $node, ".//span[@class='location']"),
            'images' => $this->extractImages($xpath, $node, ".//img"),
            'source_url' => $this->extractLink($xpath, $node, ".//a[@class='property-link']"),
            'external_id' => $this->extractExternalId($xpath, $node),
            'type' => $this->determineType($xpath, $node),
        ];
    }
    
    return $properties;
}
```

### ใช้ Browsershot/Playwright (สำหรับ JavaScript-heavy sites)

**ติดตั้ง:**
```bash
composer require spatie/browsershot
# หรือ
composer require spatie/playwright
```

**แก้ไข ScraperService:**
```php
use Spatie\Browsershot\Browsershot;

protected function fetchHtml(string $url): ?string
{
    try {
        $html = Browsershot::url($url)
            ->waitUntilNetworkIdle()
            ->bodyHtml();
        
        return $html;
    } catch (\Exception $e) {
        Log::error('Error fetching HTML with Browsershot', [
            'url' => $url,
            'error' => $e->getMessage()
        ]);
        return null;
    }
}
```

## 📊 ตรวจสอบผลลัพธ์

### ดู Scraper Logs

1. ไปที่ `/admin/scraper`
2. ดูตาราง Scraping Logs
3. ตรวจสอบ:
   - Status (success/failed/partial)
   - Properties Found vs Properties Saved
   - Execution Time
   - Error Messages

### ดู Properties ที่ Scrape มา

1. ไปที่ `/admin/properties`
2. Filter โดย Status = "Pending"
3. ตรวจสอบข้อมูลที่ scrape มา

### ดู Queue Jobs

```bash
# ดู jobs ที่ pending
php artisan queue:monitor

# ดู failed jobs
php artisan queue:failed
```

## ⚙️ Configuration Options

### config/scraper.php

- **urls**: รายการ URLs ที่จะ scrape
- **interval_hours**: ระยะเวลาระหว่างการ scrape (default: 24 ชั่วโมง)
- **delay_between_jobs**: หน่วงเวลาระหว่าง jobs (default: 2 วินาที)
- **request**: HTTP request settings
- **parsing**: CSS selectors/XPath สำหรับ parse HTML
- **images**: การตั้งค่าการดาวน์โหลดรูปภาพ
- **defaults**: ค่า default สำหรับ properties

## 🔍 Troubleshooting

### ปัญหา: Jobs ไม่ทำงาน

**แก้ไข:**
1. ตรวจสอบว่า Queue Worker กำลังรัน: `php artisan queue:work`
2. ตรวจสอบ Queue Connection ใน `.env`
3. ตรวจสอบ Logs: `storage/logs/laravel.log`

### ปัญหา: Scraping ล้มเหลว

**แก้ไข:**
1. ตรวจสอบ Error Message ใน Scraper Logs
2. ตรวจสอบว่า URL ถูกต้องและเข้าถึงได้
3. ตรวจสอบ Network/Timeout settings
4. ดู Laravel Logs สำหรับรายละเอียด

### ปัญหา: ไม่พบ Properties

**แก้ไข:**
1. ตรวจสอบว่า `parseProperties()` method ถูกต้อง
2. ตรวจสอบ CSS Selectors/XPath ว่าตรงกับโครงสร้าง HTML
3. Test parsing ด้วย HTML sample

### ปัญหา: Images ไม่ดาวน์โหลด

**แก้ไข:**
1. ตรวจสอบ Storage permissions
2. ตรวจสอบว่า `storage/app/public` มี symlink
3. ตรวจสอบ Image URLs ว่าถูกต้อง
4. ตรวจสอบ Max Image Size settings

## 📈 Best Practices

1. **Rate Limiting**: หน่วงเวลาระหว่าง requests เพื่อไม่ให้กระทบ server ต้นทาง
2. **Error Handling**: จัดการ errors อย่างเหมาะสมและบันทึก logs
3. **Deduplication**: ตรวจสอบข้อมูลซ้ำก่อนบันทึก
4. **Monitoring**: ติดตาม success rate และ error rate
5. **Testing**: Test parsing logic ด้วย HTML samples ก่อน deploy
6. **Queue Management**: ใช้ Queue สำหรับงานหนัก เพื่อไม่ให้กระทบ Web Server

## 🔐 Security Considerations

1. **User-Agent**: ใช้ User-Agent ที่เหมาะสม
2. **Rate Limiting**: อย่า scrape บ่อยเกินไป
3. **Respect robots.txt**: ตรวจสอบ robots.txt ของเว็บต้นทาง
4. **Legal Compliance**: ตรวจสอบ Terms of Service

## 📝 Next Steps

1. **ปรับแต่ง Parsing Logic**: แก้ไข `parseProperties()` ให้ตรงกับเว็บต้นทาง
2. **เพิ่ม Selectors**: ตั้งค่า CSS selectors ใน `config/scraper.php`
3. **Test Scraping**: ทดสอบกับ URL จริง
4. **Monitor Results**: ตรวจสอบผลลัพธ์และปรับแต่ง

---

**Last Updated**: 2024-12-26  
**Status**: Ready for Customization

