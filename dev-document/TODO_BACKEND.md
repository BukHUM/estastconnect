# 📝 TODO: ส่วนที่ต้องเขียนโค้ดต่อ - Backend Development

## ✅ ส่วนที่เสร็จแล้ว

- ✅ Authentication & Authorization (Login, Logout, Role-based)
- ✅ Admin Dashboard (สถิติภาพรวม)
- ✅ Property Management (CRUD พื้นฐาน)
- ✅ User Management (CRUD)
- ✅ Lead Management (ดูรายการ, Filter, Search)
- ✅ Scraper Logs (ดู Logs)
- ✅ Admin Layout & UI/UX

---

## 🔨 ส่วนที่ต้องทำต่อ (เรียงตามความสำคัญ)

### 1. 🤖 AI Rewrite Feature (สำคัญมาก)

**ไฟล์ที่ต้องสร้าง/แก้ไข:**

#### Controller Method
- **ไฟล์**: `app/Http/Controllers/Admin/PropertyController.php`
- **Method**: `aiRewrite(Property $property)`
- **หน้าที่**: 
  - รับคำขอจาก AJAX
  - เรียก OpenAI API เพื่อ Rewrite description
  - บันทึกผลลัพธ์ใน `ai_description`
  - Return JSON response

#### Route
- **ไฟล์**: `routes/web.php`
- **Route**: `POST /admin/properties/{property}/ai-rewrite`
- **ชื่อ**: `admin.properties.ai-rewrite`

#### View (Button ในหน้า Properties)
- **ไฟล์**: `resources/views/admin/properties/index.blade.php`
- **เพิ่ม**: ปุ่ม "AI Rewrite" ที่ทำงานด้วย AJAX
- **ไฟล์**: `resources/views/admin/properties/show.blade.php`
- **เพิ่ม**: ปุ่ม "AI Rewrite" และแสดงผลลัพธ์

#### Service Class (แนะนำ)
- **ไฟล์**: `app/Services/OpenAIService.php` (สร้างใหม่)
- **หน้าที่**: จัดการการเรียก OpenAI API

**ตัวอย่าง Code:**
```php
// app/Services/OpenAIService.php
public function rewriteDescription(string $originalDescription): string
{
    // เรียก OpenAI API
    // Return rewritten text
}
```

---

### 2. 🔄 Scraper System (สำคัญมาก)

**ไฟล์ที่ต้องสร้าง/แก้ไข:**

#### Job Class
- **ไฟล์**: `app/Jobs/ScrapePropertyJob.php` (สร้างใหม่)
- **หน้าที่**: 
  - ดึงข้อมูลจากเว็บต้นทาง
  - Parse HTML
  - บันทึกข้อมูลลง Database
  - บันทึก Log

#### Scraper Service
- **ไฟล์**: `app/Services/ScraperService.php` (สร้างใหม่)
- **หน้าที่**: Logic การ Scrape

#### Controller Method
- **ไฟล์**: `app/Http/Controllers/Admin/ScraperController.php`
- **Method**: `run()` - แก้ไขให้ Dispatch Job จริง

#### Command (สำหรับ Scheduled Scraping)
- **ไฟล์**: `app/Console/Commands/ScrapeProperties.php` (สร้างใหม่)
- **หน้าที่**: Command สำหรับรัน Scraper ตาม Schedule

#### Config
- **ไฟล์**: `config/scraper.php` (สร้างใหม่)
- **หน้าที่**: เก็บ URL ที่จะ Scrape, Settings

**ตัวอย่าง Code:**
```php
// app/Jobs/ScrapePropertyJob.php
public function handle()
{
    // 1. ดึง HTML จาก URL
    // 2. Parse ข้อมูล (Title, Price, Description, Images)
    // 3. Download Images
    // 4. บันทึก Property
    // 5. บันทึก ScraperLog
}
```

---

### 3. 📤 Lead Export Feature

**ไฟล์ที่ต้องแก้ไข:**

#### Controller Method
- **ไฟล์**: `app/Http/Controllers/Admin/LeadController.php`
- **Method**: `export(Request $request)`
- **หน้าที่**: Export Leads เป็น CSV/Excel

**Option 1: CSV (ง่าย)**
```php
public function export(Request $request)
{
    $leads = Lead::with('property')->get();
    
    $filename = 'leads-' . date('Y-m-d') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ];
    
    $callback = function() use ($leads) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Name', 'Phone', 'Email', 'Property', 'Date']);
        
        foreach ($leads as $lead) {
            fputcsv($file, [
                $lead->name,
                $lead->phone,
                $lead->email,
                $lead->property->title ?? '-',
                $lead->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}
```

**Option 2: Excel (ต้องติดตั้ง Package)**
```bash
composer require maatwebsite/excel
```

---

### 4. 🖼️ Media Management

**ไฟล์ที่ต้องสร้าง/แก้ไข:**

#### Controller
- **ไฟล์**: `app/Http/Controllers/Admin/MediaController.php` (สร้างใหม่)
- **Methods**:
  - `store(Request $request, Property $property)` - อัปโหลดรูป
  - `destroy(Media $media)` - ลบรูป
  - `setMain(Media $media)` - ตั้งเป็นรูปหลัก
  - `reorder(Request $request)` - จัดลำดับรูป

#### Routes
- **ไฟล์**: `routes/web.php`
```php
Route::post('/properties/{property}/media', [MediaController::class, 'store']);
Route::delete('/media/{media}', [MediaController::class, 'destroy']);
Route::post('/media/{media}/set-main', [MediaController::class, 'setMain']);
Route::post('/media/reorder', [MediaController::class, 'reorder']);
```

#### Views
- **ไฟล์**: `resources/views/admin/properties/show.blade.php`
- **เพิ่ม**: Form อัปโหลดรูป, Drag & Drop, จัดลำดับ

---

### 5. 🔗 Affiliate Links Management

**ไฟล์ที่ต้องสร้าง/แก้ไข:**

#### Controller
- **ไฟล์**: `app/Http/Controllers/Admin/AffiliateLinkController.php` (สร้างใหม่)
- **Methods**:
  - `store(Request $request, Property $property)` - เพิ่ม Link
  - `update(Request $request, AffiliateLink $link)` - แก้ไข Link
  - `destroy(AffiliateLink $link)` - ลบ Link

#### Routes
- **ไฟล์**: `routes/web.php`
```php
Route::post('/properties/{property}/affiliate-links', [AffiliateLinkController::class, 'store']);
Route::put('/affiliate-links/{link}', [AffiliateLinkController::class, 'update']);
Route::delete('/affiliate-links/{link}', [AffiliateLinkController::class, 'destroy']);
```

#### Views
- **ไฟล์**: `resources/views/admin/properties/show.blade.php`
- **เพิ่ม**: Form เพิ่ม/แก้ไข Affiliate Links

---

### 6. 👁️ Lead Detail View

**ไฟล์ที่ต้องสร้าง:**

#### View
- **ไฟล์**: `resources/views/admin/leads/show.blade.php` (สร้างใหม่)
- **หน้าที่**: แสดงรายละเอียด Lead แบบเต็ม

---

### 7. 📊 Additional Features (Optional)

#### Property Search Enhancement
- **ไฟล์**: `app/Http/Controllers/Admin/PropertyController.php`
- **เพิ่ม**: Full-text Search, Advanced Filters

#### Bulk Actions
- **ไฟล์**: `app/Http/Controllers/Admin/PropertyController.php`
- **เพิ่ม**: Bulk Publish, Bulk Delete, Bulk Export

#### Activity Log
- **ไฟล์**: `app/Models/ActivityLog.php` (สร้างใหม่)
- **หน้าที่**: บันทึกการกระทำของ Admin

---

## 📂 โครงสร้างไฟล์ที่ต้องสร้าง

```
app/
├── Http/
│   └── Controllers/
│       └── Admin/
│           └── MediaController.php          [ต้องสร้าง]
│           └── AffiliateLinkController.php  [ต้องสร้าง]
├── Jobs/
│   └── ScrapePropertyJob.php               [ต้องสร้าง]
├── Services/
│   └── OpenAIService.php                    [ต้องสร้าง]
│   └── ScraperService.php                   [ต้องสร้าง]
└── Console/
    └── Commands/
        └── ScrapeProperties.php              [ต้องสร้าง]

resources/
└── views/
    └── admin/
        ├── leads/
        │   └── show.blade.php                [ต้องสร้าง]
        └── properties/
            └── _media-form.blade.php         [Optional - Component]

config/
└── scraper.php                               [ต้องสร้าง]
```

---

## 🎯 ลำดับความสำคัญในการพัฒนา

### Phase 1: Core Features (ทำก่อน)
1. ✅ AI Rewrite Feature
2. ✅ Scraper System
3. ✅ Lead Export

### Phase 2: Management Features
4. ✅ Media Management
5. ✅ Affiliate Links Management
6. ✅ Lead Detail View

### Phase 3: Enhancements
7. ✅ Advanced Search
8. ✅ Bulk Actions
9. ✅ Activity Log

---

## 🔧 Dependencies ที่ต้องติดตั้ง

```bash
# สำหรับ Scraping
composer require spatie/browsershot
# หรือ
composer require spatie/playwright

# สำหรับ Excel Export (ถ้าใช้)
composer require maatwebsite/excel

# สำหรับ OpenAI API
composer require openai-php/laravel
# หรือใช้ HTTP Client ธรรมดา
```

---

## 📝 หมายเหตุ

- ตรวจสอบว่า Models มี Relationships ครบถ้วน
- ตรวจสอบว่า Migrations มี Fields ครบตามที่ต้องการ
- ควรเขียน Tests สำหรับ Features สำคัญ
- ใช้ Queue สำหรับงานหนัก (Scraping, Image Processing)

---

**Last Updated**: 2024-12-26  
**Status**: In Progress

