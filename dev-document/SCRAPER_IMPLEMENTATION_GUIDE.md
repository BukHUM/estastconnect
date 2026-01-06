# 🔧 Scraper Implementation Guide - คู่มือการปรับแต่ง Scraper

## 📋 ภาพรวม

Scraper System ถูกสร้างขึ้นมาแล้ว แต่ต้องปรับแต่ง `parseProperties()` method ใน `ScraperService` ให้ตรงกับโครงสร้าง HTML ของเว็บต้นทาง

## 🎯 ขั้นตอนการปรับแต่ง

### 1. วิเคราะห์โครงสร้าง HTML ของเว็บต้นทาง

1. เปิดเว็บที่ต้องการ scrape
2. เปิด Browser DevTools (F12)
3. Inspect element ของ property card/item
4. ดูโครงสร้าง HTML และ CSS classes/IDs

**ตัวอย่างโครงสร้างที่อาจพบ:**
```html
<div class="property-card">
    <h2 class="property-title">ชื่อโครงการ</h2>
    <span class="property-price">฿5,000,000</span>
    <p class="property-description">คำอธิบาย...</p>
    <span class="property-location">ที่ตั้ง</span>
    <img src="image.jpg" class="property-image">
    <a href="/property/123" class="property-link">ดูรายละเอียด</a>
</div>
```

### 2. แก้ไข parseProperties() Method

ไฟล์: `app/Services/ScraperService.php`

**ตัวอย่างการใช้งาน:**

```php
protected function parseProperties(string $html, string $baseUrl): array
{
    $properties = [];
    
    try {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);

        // หา property containers (ปรับตามโครงสร้างจริง)
        $propertyNodes = $xpath->query("//div[@class='property-card']");
        
        foreach ($propertyNodes as $node) {
            // Extract title
            $title = $this->extractText($xpath, $node, ".//h2[@class='property-title']");
            
            // Extract price
            $priceText = $this->extractText($xpath, $node, ".//span[@class='property-price']");
            $price = $this->parsePrice($priceText);
            
            // Extract description
            $description = $this->extractText($xpath, $node, ".//p[@class='property-description']");
            
            // Extract location
            $location = $this->extractText($xpath, $node, ".//span[@class='property-location']");
            
            // Extract images
            $images = [];
            $imageNodes = $xpath->query(".//img[@class='property-image']", $node);
            foreach ($imageNodes as $imgNode) {
                $imgUrl = $imgNode->getAttribute('src');
                if ($imgUrl) {
                    $images[] = $this->resolveUrl($imgUrl, $baseUrl);
                }
            }
            
            // Extract property link
            $linkNode = $xpath->query(".//a[@class='property-link']", $node)->item(0);
            $propertyUrl = $linkNode ? $this->resolveUrl($linkNode->getAttribute('href'), $baseUrl) : null;
            
            // Extract external ID
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
        
    } catch (\Exception $e) {
        Log::error('Error parsing properties', [
            'url' => $baseUrl,
            'error' => $e->getMessage()
        ]);
    }
    
    return $properties;
}
```

### 3. ตั้งค่า CSS Selectors ใน Config (Optional)

ไฟล์: `config/scraper.php`

```php
'parsing' => [
    'property_container' => '.property-card',
    'title_selector' => '.property-title',
    'price_selector' => '.property-price',
    'description_selector' => '.property-description',
    'location_selector' => '.property-location',
    'image_selector' => '.property-image',
    'link_selector' => '.property-link',
],
```

## 🔍 XPath vs CSS Selectors

### XPath Examples

```php
// หา element โดย class
$xpath->query("//div[@class='property-card']")

// หา element ที่มี class หลายตัว
$xpath->query("//div[contains(@class, 'property')]")

// หา element โดย ID
$xpath->query("//div[@id='property-list']")

// หา element ที่มี attribute
$xpath->query("//a[@href]")

// หา element ลูก
$xpath->query(".//h2", $contextNode)

// หา element ที่มี text
$xpath->query("//span[text()='ราคา']")
```

### CSS Selectors (ต้องใช้ library เพิ่ม)

```bash
composer require symfony/css-selector
```

```php
use Symfony\Component\CssSelector\CssSelectorConverter;

$converter = new CssSelectorConverter();
$xpathQuery = $converter->toXPath('.property-card');
$nodes = $xpath->query($xpathQuery);
```

## 🧪 Testing

### 1. Test Parsing Logic

สร้างไฟล์ test:

```php
// tests/Feature/ScraperTest.php
public function test_parse_properties()
{
    $html = file_get_contents('tests/fixtures/sample-property-page.html');
    $scraper = new ScraperService();
    
    $reflection = new \ReflectionClass($scraper);
    $method = $reflection->getMethod('parseProperties');
    $method->setAccessible(true);
    
    $properties = $method->invoke($scraper, $html, 'https://example.com');
    
    $this->assertNotEmpty($properties);
    $this->assertArrayHasKey('title', $properties[0]);
    $this->assertArrayHasKey('price', $properties[0]);
}
```

### 2. Test ด้วย URL จริง

```bash
php artisan scraper:run --url=https://example.com/properties
```

### 3. ตรวจสอบ Logs

```bash
tail -f storage/logs/laravel.log
```

## 📝 ตัวอย่างการ Parse หลายรูปแบบ

### ตัวอย่างที่ 1: Simple List

```html
<ul class="property-list">
    <li class="property-item">
        <h3>ชื่อโครงการ</h3>
        <span>฿5,000,000</span>
    </li>
</ul>
```

```php
$propertyNodes = $xpath->query("//li[@class='property-item']");
```

### ตัวอย่างที่ 2: Grid Layout

```html
<div class="property-grid">
    <article class="property-card">
        <img src="image.jpg">
        <div class="property-info">
            <h2>ชื่อโครงการ</h2>
            <p class="price">฿5,000,000</p>
        </div>
    </article>
</div>
```

```php
$propertyNodes = $xpath->query("//article[@class='property-card']");
```

### ตัวอย่างที่ 3: Table Layout

```html
<table class="properties">
    <tr class="property-row">
        <td class="title">ชื่อโครงการ</td>
        <td class="price">฿5,000,000</td>
    </tr>
</table>
```

```php
$propertyNodes = $xpath->query("//tr[@class='property-row']");
```

## 🚨 Common Issues

### Issue 1: ไม่พบ Properties

**สาเหตุ**: CSS Selector/XPath ไม่ตรงกับโครงสร้าง HTML

**แก้ไข**: 
1. ตรวจสอบ HTML structure ด้วย DevTools
2. ทดสอบ XPath query ก่อน
3. ใช้ `var_dump()` หรือ `Log::info()` เพื่อ debug

### Issue 2: ได้ข้อมูลไม่ครบ

**สาเหตุ**: Selector ไม่ครอบคลุมทุก field

**แก้ไข**: 
1. ตรวจสอบว่า selector ถูกต้อง
2. เพิ่ม fallback selectors
3. Handle null values

### Issue 3: Images ไม่ดาวน์โหลด

**สาเหตุ**: 
- Image URLs เป็น relative paths
- Images ถูก load ด้วย JavaScript

**แก้ไข**:
1. ใช้ `resolveUrl()` helper
2. ใช้ Browsershot/Playwright สำหรับ JavaScript-heavy sites

## 🔄 Next Steps

1. **วิเคราะห์เว็บต้นทาง**: ดูโครงสร้าง HTML จริง
2. **Implement Parsing**: แก้ไข `parseProperties()` method
3. **Test**: ทดสอบกับ URL จริง
4. **Refine**: ปรับแต่งให้ได้ข้อมูลครบถ้วน
5. **Monitor**: ตรวจสอบ logs และ results

---

**Last Updated**: 2024-12-26

