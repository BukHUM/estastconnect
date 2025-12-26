📑 แผนงานการพัฒนาระบบ Real Estate Affiliate Platform (PropFinder)

สรุปขั้นตอนการสร้างระบบดึงข้อมูลอสังหาริมทรัพย์ (Scraping) มาบริหารจัดการด้วย AI และสร้างหน้ารับลูกค้า (Lead Generation) เพื่อส่งต่อให้ระบบ Affiliate (Prop2Share)

🏗️ 1. สถาปัตยกรรมระบบ (System Architecture)

ระบบประกอบด้วย 3 ส่วนหลักที่ทำงานประสานกัน:

**Scraping Engine**: ทำหน้าที่ดึงข้อมูลดิบจากเว็บต้นทาง (เช่น Prop2Share) โดยใช้ Queue System เพื่อแยกการประมวลผลออกจาก Web Server

**Admin Dashboard (Backend)**: จัดการข้อมูลดิบ, ตรวจสอบ, และใช้ AI ปรับปรุงเนื้อหา พัฒนาเองด้วย Laravel + Blade + Tailwind CSS เพื่อให้สามารถปรับแต่งได้ตามต้องการ

**Property Portal (Frontend)**: หน้าเว็บสำหรับผู้ใช้งานทั่วไป เพื่อค้นหาและกรอกข้อมูลสนใจ (Lead) พร้อมระบบ SEO และ Lead Capture

🛠️ 2. เทคโนโลยีที่ใช้ (Tech Stack)

**Backend Framework**: Laravel 12+ (PHP 8.2+)

**Admin Panel**: Custom Development (Laravel Blade + Tailwind CSS + Alpine.js) - พัฒนาเองเพื่อให้สามารถปรับแต่งได้ตามต้องการ ไม่ใช้ Third-party Admin Panel

**Frontend**: Blade Templates + Tailwind CSS + Lucide Icons + Alpine.js

**Database**: MySQL 8.0+ (รองรับ JSON columns และ Full-text search)

**Queue System**: Redis (แนะนำ) หรือ Database Queue สำหรับจัดการ Scraping Jobs

**Cache**: Redis หรือ Memcached สำหรับเก็บข้อมูลที่ใช้บ่อย

**File Storage**: Local Storage (เริ่มต้น) / AWS S3 / Cloud Storage (สำหรับ Production)

**Scraping Tools**: 
- Spatie/Browsershot (Puppeteer) - สำหรับ JavaScript-heavy sites
- หรือ Playwright - ทางเลือกที่เร็วและเสถียรกว่า

**AI Integration**: OpenAI API (GPT-4o) สำหรับการ Rewrite เนื้อหา

**Search Engine**: Laravel Scout + MySQL Full-text หรือ Algolia (ถ้าต้องการ)

**Infrastructure**: 
- Development: XAMPP / Laravel Sail
- Production: aaPanel / Docker / Kubernetes / Proxmox

**Monitoring & Logging**: 
- Laravel Pail (Development)
- Laravel Telescope (Development/Staging)
- Log Files + Loki (Production)

💾 3. โครงสร้างฐานข้อมูลที่สำคัญ (Database Schema)

### 3.1 ตาราง Properties

| Field | Type | Description | Index |
|-------|------|-------------|-------|
| id | BIGINT UNSIGNED | Primary Key | PRIMARY |
| title | VARCHAR(255) | ชื่อโครงการ | FULLTEXT |
| slug | VARCHAR(255) | URL-friendly name | UNIQUE |
| description | TEXT | คำอธิบายโครงการ | FULLTEXT |
| ai_description | TEXT | คำอธิบายที่ AI rewrite แล้ว | - |
| price | DECIMAL(15,2) | ราคาเริ่มต้น | INDEX |
| type | VARCHAR(50) | ประเภท (condo/house/land) | INDEX |
| location | VARCHAR(255) | ที่ตั้ง | INDEX |
| district | VARCHAR(100) | เขต/อำเภอ | INDEX |
| province | VARCHAR(100) | จังหวัด | INDEX |
| status | ENUM | pending/published/archived | INDEX |
| source_url | VARCHAR(500) | URL ต้นทาง | - |
| external_id | VARCHAR(100) | ID จากระบบต้นทาง | INDEX |
| view_count | INT UNSIGNED | จำนวนการดู | - |
| click_count | INT UNSIGNED | จำนวนการคลิก | - |
| created_at | TIMESTAMP | - | INDEX |
| updated_at | TIMESTAMP | - | - |
| deleted_at | TIMESTAMP | Soft Delete | - |

**Relationships**: 
- hasMany Media
- hasMany AffiliateLinks
- hasMany Leads

### 3.2 ตาราง Media

| Field | Type | Description | Index |
|-------|------|-------------|-------|
| id | BIGINT UNSIGNED | Primary Key | PRIMARY |
| property_id | BIGINT UNSIGNED | Foreign Key | INDEX |
| image_url | VARCHAR(500) | URL ต้นทาง | - |
| local_path | VARCHAR(500) | Path ใน Storage | - |
| is_main | BOOLEAN | รูปหลัก | INDEX |
| order | INT | ลำดับการแสดง | INDEX |
| file_size | INT | ขนาดไฟล์ (bytes) | - |
| mime_type | VARCHAR(50) | ประเภทไฟล์ | - |
| created_at | TIMESTAMP | - | - |
| updated_at | TIMESTAMP | - | - |

**Relationships**: 
- belongsTo Property

### 3.3 ตาราง Affiliate_Links

| Field | Type | Description | Index |
|-------|------|-------------|-------|
| id | BIGINT UNSIGNED | Primary Key | PRIMARY |
| property_id | BIGINT UNSIGNED | Foreign Key | INDEX |
| provider | VARCHAR(50) | Prop2Share, etc. | INDEX |
| link_url | VARCHAR(500) | URL Affiliate | - |
| click_count | INT UNSIGNED | จำนวนการคลิก | - |
| last_clicked_at | TIMESTAMP | ครั้งล่าสุดที่คลิก | - |
| is_active | BOOLEAN | สถานะใช้งาน | INDEX |
| created_at | TIMESTAMP | - | - |
| updated_at | TIMESTAMP | - | - |

**Relationships**: 
- belongsTo Property

### 3.4 ตาราง Leads

| Field | Type | Description | Index |
|-------|------|-------------|-------|
| id | BIGINT UNSIGNED | Primary Key | PRIMARY |
| property_id | BIGINT UNSIGNED | Foreign Key | INDEX |
| name | VARCHAR(255) | ชื่อ-นามสกุล | - |
| phone | VARCHAR(20) | เบอร์โทรศัพท์ | INDEX |
| email | VARCHAR(255) | อีเมล | INDEX |
| ip_address | VARCHAR(45) | IP Address | INDEX |
| user_agent | TEXT | Browser Info | - |
| converted_at | TIMESTAMP | วันที่ Convert | INDEX |
| source | VARCHAR(50) | ที่มาของ Lead | INDEX |
| notes | TEXT | หมายเหตุ | - |
| created_at | TIMESTAMP | - | INDEX |
| updated_at | TIMESTAMP | - | - |

**Relationships**: 
- belongsTo Property

### 3.5 ตาราง Scraper_Logs

| Field | Type | Description | Index |
|-------|------|-------------|-------|
| id | BIGINT UNSIGNED | Primary Key | PRIMARY |
| url | VARCHAR(500) | URL ที่ Scrape | INDEX |
| status | ENUM | success/failed/partial | INDEX |
| error_message | TEXT | ข้อความ Error | - |
| properties_found | INT | จำนวนทรัพย์ที่พบ | - |
| properties_saved | INT | จำนวนทรัพย์ที่บันทึก | - |
| execution_time | INT | เวลาที่ใช้ (seconds) | - |
| last_scraped_at | TIMESTAMP | ครั้งล่าสุดที่ Scrape | INDEX |
| next_scrape_at | TIMESTAMP | ครั้งถัดไปที่ควร Scrape | INDEX |
| created_at | TIMESTAMP | - | INDEX |
| updated_at | TIMESTAMP | - | - |

### 3.6 ตาราง Users (Laravel Default + Extensions)

| Field | Type | Description | Index |
|-------|------|-------------|-------|
| id | BIGINT UNSIGNED | Primary Key | PRIMARY |
| name | VARCHAR(255) | ชื่อ | - |
| email | VARCHAR(255) | อีเมล | UNIQUE |
| email_verified_at | TIMESTAMP | - | - |
| password | VARCHAR(255) | รหัสผ่าน (hashed) | - |
| role | ENUM | admin/editor/viewer | INDEX |
| remember_token | VARCHAR(100) | - | - |
| created_at | TIMESTAMP | - | - |
| updated_at | TIMESTAMP | - | - |

🔐 4. Authentication & Authorization

**Authentication**: Laravel Breeze / Laravel UI (Session-based)

**Authorization**: Role-based Access Control (RBAC)
- **Admin**: สิทธิ์เต็ม (จัดการทุกอย่าง)
- **Editor**: แก้ไขและ Publish ทรัพย์สิน
- **Viewer**: ดูข้อมูลเท่านั้น

**Middleware**: 
- `auth` - ต้อง Login
- `role:admin` - ต้องเป็น Admin
- `role:admin,editor` - ต้องเป็น Admin หรือ Editor

**API Authentication**: Sanctum (ถ้ามี API endpoints)

🖥️ 4.1 Admin Panel Architecture (Custom Development)

**Technology Stack**:
- **Backend**: Laravel Controllers + Blade Templates
- **Frontend**: Tailwind CSS + Alpine.js + Lucide Icons
- **Layout**: Custom Admin Layout Component
- **Components**: Reusable Blade Components

**Main Features**:
- **Dashboard Overview**: สถิติภาพรวม (ทรัพย์สินรอตรวจสอบ, เผยแพร่แล้ว, Leads, Scraping Status)
- **Property Management**: 
  - รายการทรัพย์สิน (Table View พร้อม Pagination, Search, Filter)
  - แก้ไขทรัพย์สิน (Form Edit)
  - AI Rewrite Description (AJAX Request)
  - Publish/Unpublish ทรัพย์สิน
  - ลบทรัพย์สิน (Soft Delete)
- **Media Management**: 
  - อัปโหลด/ลบรูปภาพ
  - จัดลำดับรูปภาพ
  - ตั้งค่ารูปหลัก
- **Lead Management**: 
  - ดูรายการ Leads
  - Export Leads เป็น CSV/Excel
  - Filter Leads ตาม Property, Date Range
- **Scraper Management**:
  - ดู Scraping Logs
  - รัน Scraper แบบ Manual
  - ตั้งค่า Scraping Schedule
  - ดู Statistics (Success Rate, Error Rate)
- **User Management**: 
  - จัดการ Users และ Roles
  - เปลี่ยน Password
  - ตั้งค่า Permissions

**UI/UX Design**:
- **Sidebar Navigation**: เมนูหลักทางซ้าย
- **Top Bar**: Search, Notifications, User Profile
- **Content Area**: Dynamic Content ตามหน้าที่เลือก
- **Responsive Design**: รองรับ Mobile และ Tablet
- **Dark Mode**: (Optional) สำหรับ Admin ที่ต้องการ

**Advantages of Custom Development**:
- **Full Control**: ปรับแต่งได้ทุกส่วนตามต้องการ
- **No Dependencies**: ไม่ต้องพึ่ง Third-party Package ที่อาจมีข้อจำกัด
- **Performance**: เบากว่าและเร็วขึ้น (ไม่มี Overhead จาก Admin Panel Framework)
- **Custom Features**: เพิ่ม Features เฉพาะได้ง่าย
- **Branding**: ใช้ Design ที่ตรงกับ Brand Identity

🌐 5. API Endpoints (ถ้ามี)

**Public API** (สำหรับ Frontend):
- `GET /api/properties` - รายการทรัพย์สิน (พร้อม Pagination, Filter, Search)
- `GET /api/properties/{slug}` - รายละเอียดทรัพย์สิน
- `POST /api/leads` - ส่งข้อมูล Lead

**Admin API** (ต้อง Authentication):
- `GET /api/admin/properties` - จัดการทรัพย์สิน
- `POST /api/admin/properties/{id}/ai-rewrite` - สั่ง AI Rewrite
- `POST /api/admin/properties/{id}/publish` - Publish ทรัพย์สิน
- `GET /api/admin/scraper/run` - รัน Scraper แบบ Manual

🚀 6. ขั้นตอนการทำงาน (Step-by-Step Workflow)

### 6.1 ระยะที่ 1: การดึงข้อมูล (Data Acquisition)

**Job Scheduling**: 
- ตั้งค่า Laravel Task Scheduling (`app/Console/Kernel.php`) ให้รัน Scraper ตามเวลาที่กำหนด
- ตัวอย่าง: ทุกวันเวลา 02:00 น. (ช่วงที่ Traffic ต่ำ)
- ใช้ `php artisan schedule:work` (Development) หรือ Cron Job (Production)

**Scraping Process**:
1. ดึง URL List จาก Config หรือ Database
2. สร้าง Queue Job สำหรับแต่ละ URL
3. ดึงข้อมูล HTML จากเว็บต้นทาง (ใช้ Browsershot/Playwright)
4. Parse HTML เพื่อดึงข้อมูล (Title, Price, Description, Images, etc.)
5. ดาวน์โหลดและเก็บภาพลง Storage (Local/S3)
6. บันทึกข้อมูลลงตาราง Properties โดยตั้งค่า `status = 'pending'`
7. บันทึก Log ลงตาราง Scraper_Logs

**Deduplication**: 
- เช็ค `external_id` หรือ `source_url` เพื่อไม่ให้ข้อมูลซ้ำ
- ถ้าพบข้อมูลเดิม ให้ Update แทนการ Insert ใหม่
- ใช้ Database Unique Constraint หรือ Application Logic

**Error Handling**:
- ถ้า Scraping ล้มเหลว ให้บันทึก Error ลง Scraper_Logs
- ตั้งค่า Retry Mechanism (Laravel Queue: `--tries=3`)
- ส่ง Notification ไปยัง Admin เมื่อมี Error จำนวนมาก

### 6.2 ระยะที่ 2: การจัดการหลังบ้าน (Content Management)

**Review Process**:
- Admin ตรวจสอบข้อมูลที่ดึงมาผ่านหน้า Dashboard (Custom Admin Panel)
- แสดงรายการทรัพย์สินที่ `status = 'pending'` พร้อม Filter และ Search
- สามารถแก้ไขข้อมูลเบื้องต้นได้ (Title, Price, Location, Description)
- แสดง Statistics: จำนวนทรัพย์สินรอตรวจสอบ, เผยแพร่แล้ว, Scrap พลาด

**AI Transformation**:
- กดปุ่ม "AI Rewrite" เพื่อส่ง `description` เดิมไปให้ GPT-4o
- Prompt: "Rewrite this property description in Thai, make it SEO-friendly and engaging, keep key information like location, price, and features"
- เก็บผลลัพธ์ไว้ใน `ai_description` (ยังไม่ Publish)
- Admin สามารถแก้ไขหรือยอมรับได้

**Verification**:
- ตรวจสอบความถูกต้องของราคาและลิงก์ Affiliate
- ตรวจสอบรูปภาพว่าดาวน์โหลดครบหรือไม่
- ตรวจสอบข้อมูลที่จำเป็นครบถ้วน

**Publishing**:
- กดปุ่ม "Publish" เพื่อเปลี่ยน `status = 'published'`
- ใช้ `ai_description` แทน `description` (ถ้ามี)
- สร้าง Slug อัตโนมัติจาก Title (ถ้ายังไม่มี)
- ส่ง Notification ว่า Publish สำเร็จ

### 6.3 ระยะที่ 3: หน้าเว็บและการตลาด (Frontend & Lead Gen)

**SEO Listing**:
- แสดงรายการโครงการด้วย URL ที่สวยงาม (`/properties/{slug}`)
- ใช้ Laravel Route Model Binding
- เพิ่ม Meta Tags (Title, Description, OG Tags) สำหรับ SEO
- ใช้ Sitemap.xml สำหรับ Google Search Console

**Search & Filter**:
- ค้นหาด้วยชื่อโครงการ, ทำเล, ประเภท
- Filter ด้วยราคา, ประเภท, จังหวัด
- ใช้ MySQL Full-text Search หรือ Laravel Scout

**Lead Capture**:
- เมื่อลูกค้าสนใจโครงการ จะต้องผ่านหน้า Intermediate Modal
- กรอกข้อมูล: ชื่อ-นามสกุล, เบอร์โทรศัพท์, อีเมล (ไม่บังคับ)
- Validate ข้อมูลเบื้องต้น (เบอร์โทร 10 หลัก, อีเมล format)

**Tracking & Redirect**:
1. บันทึกข้อมูล Lead ลง Database (`Leads` table)
2. บันทึก IP Address และ User Agent
3. ยิง Event ไปที่ Google Analytics (gtag.js)
4. ส่ง Webhook ไปยัง Third-party (ถ้ามี)
5. Redirect ลูกค้าไปยังลิงก์ Affiliate ต้นทาง (Prop2Share)
6. อัปเดต `click_count` ใน `Affiliate_Links` table

**Analytics**:
- Track Page Views (`view_count++`)
- Track Affiliate Clicks (`click_count++`)
- Track Lead Conversions
- Export Reports สำหรับ Admin Dashboard

🔄 7. Queue System & Background Jobs

**Queue Configuration**:
- ใช้ Redis Queue (แนะนำ) หรือ Database Queue
- แยก Queue สำหรับ Scraping (`scraping`) และ Queue ทั่วไป (`default`)
- ตั้งค่า Worker: `php artisan queue:work --queue=scraping,default`

**Job Classes**:
- `ScrapePropertyJob`: ดึงข้อมูลทรัพย์สินแต่ละรายการ
- `DownloadImageJob`: ดาวน์โหลดรูปภาพ
- `AiRewriteJob`: ส่งคำขอไปยัง OpenAI API
- `SendNotificationJob`: ส่ง Email/Notification

**Retry Mechanism**:
- ตั้งค่า `--tries=3` สำหรับ Jobs ที่ล้มเหลว
- ใช้ `--backoff=60` เพื่อหน่วงเวลาก่อน Retry
- เก็บ Failed Jobs ไว้ใน `failed_jobs` table เพื่อตรวจสอบ

**Rate Limiting**:
- หน่วงเวลา 2-5 วินาที ระหว่างการ Scrape แต่ละ URL
- ใช้ Laravel Rate Limiting Middleware
- จำกัดจำนวน Concurrent Jobs

💾 8. Caching Strategy

**Cache Layers**:
- **Application Cache**: เก็บข้อมูล Properties ที่ใช้บ่อย (TTL: 1 ชั่วโมง)
- **Query Cache**: Cache Database Queries ที่ซับซ้อน
- **View Cache**: Cache Blade Templates (Production)
- **Route Cache**: Cache Routes (Production)

**Cache Keys**:
- `properties:published:{page}` - รายการทรัพย์สินที่ Publish แล้ว
- `property:{slug}` - รายละเอียดทรัพย์สินแต่ละรายการ
- `property:count` - จำนวนทรัพย์สินทั้งหมด

**Cache Invalidation**:
- Clear Cache เมื่อมีการ Publish/Update/Delete ทรัพย์สิน
- ใช้ Laravel Cache Tags (ถ้าใช้ Redis)
- ตั้งค่า Cache Warming สำหรับข้อมูลสำคัญ

⚡ 9. Performance Optimization

**Database Optimization**:
- เพิ่ม Indexes สำหรับ Columns ที่ใช้ Query บ่อย (status, type, province, etc.)
- ใช้ Full-text Indexes สำหรับการค้นหา
- ใช้ Database Query Optimization (Eager Loading, Lazy Loading)
- ตั้งค่า Connection Pooling

**Image Optimization**:
- Resize Images เมื่อดาวน์โหลด (ใช้ Intervention Image)
- สร้าง Thumbnails สำหรับแสดงใน Listing
- ใช้ WebP Format (ถ้า Browser รองรับ)
- ใช้ CDN สำหรับ Serve Images (Production)

**Frontend Optimization**:
- Lazy Loading สำหรับ Images
- Code Splitting สำหรับ JavaScript
- Minify CSS/JS (Production)
- ใช้ Browser Caching Headers

**Server Optimization**:
- ใช้ OPcache สำหรับ PHP
- ตั้งค่า Nginx/Apache Caching
- ใช้ HTTP/2 หรือ HTTP/3
- ตั้งค่า Gzip Compression

🔒 10. Security Considerations

**Input Validation**:
- Validate ทุก Input จาก User (Laravel Form Requests)
- Sanitize HTML Input (ป้องกัน XSS)
- Validate File Uploads (Type, Size)
- Rate Limiting สำหรับ API Endpoints

**SQL Injection Prevention**:
- ใช้ Eloquent ORM หรือ Parameterized Queries
- ไม่ใช้ Raw Queries ที่มี User Input

**CSRF Protection**:
- ใช้ Laravel CSRF Tokens (มีอยู่แล้วใน Blade)
- Verify CSRF Token สำหรับ POST/PUT/DELETE Requests

**Authentication Security**:
- Hash Passwords (Laravel ทำให้อัตโนมัติ)
- ใช้ Strong Password Policy
- ตั้งค่า Session Timeout
- ใช้ HTTPS (Production)

**File Upload Security**:
- Validate File Types และ Sizes
- เก็บไฟล์นอก Web Root หรือใช้ Signed URLs
- Scan Files สำหรับ Malware (ถ้าเป็นไปได้)

**API Security**:
- ใช้ API Rate Limiting
- ใช้ API Keys หรือ OAuth (ถ้ามี Public API)
- Validate และ Sanitize API Inputs

🧪 11. Testing Strategy

**Unit Tests**:
- Test Models และ Relationships
- Test Helper Functions
- Test Form Validation Rules

**Feature Tests**:
- Test Authentication Flow
- Test Scraping Process
- Test Lead Capture Flow
- Test Admin Dashboard Functions

**Integration Tests**:
- Test API Endpoints
- Test Queue Jobs
- Test Database Migrations

**Testing Tools**:
- PHPUnit (Laravel Default)
- Laravel Dusk (Browser Testing - ถ้าจำเป็น)

📦 12. Deployment Process

**Environment Setup**:
- Development: Local (XAMPP/Laravel Sail)
- Staging: UAT Server (ทดสอบก่อน Production)
- Production: Production Server (aaPanel/Docker/K8s)

**Deployment Steps**:
1. Pull Latest Code จาก Git Repository
2. Install Dependencies: `composer install --no-dev --optimize-autoloader`
3. Copy Environment File: `.env.example` -> `.env`
4. Generate App Key: `php artisan key:generate`
5. Run Migrations: `php artisan migrate --force`
6. Clear Caches: `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`
7. Build Assets: `npm run build` (Production)
8. Set Permissions: `chmod -R 755 storage bootstrap/cache`
9. Restart Queue Workers: `php artisan queue:restart`
10. Restart Web Server (Nginx/Apache)

**CI/CD Pipeline** (ถ้ามี):
- GitLab CI / GitHub Actions
- Run Tests ก่อน Deploy
- Build Docker Images (ถ้าใช้ Docker)
- Deploy ไปยัง Staging/Production

**Rollback Strategy**:
- เก็บ Previous Version ไว้
- Rollback Database Migrations (ถ้าจำเป็น)
- Restore Previous Code Version

📊 13. Monitoring & Logging

**Application Logging**:
- Laravel Log Files (`storage/logs/laravel.log`)
- Log Levels: DEBUG, INFO, WARNING, ERROR, CRITICAL
- Log Important Events: Scraping Success/Failure, Lead Submissions, Errors

**Error Tracking**:
- Laravel Telescope (Development/Staging)
- Sentry หรือ Bugsnag (Production - ถ้ามี)
- Email Notifications สำหรับ Critical Errors

**Performance Monitoring**:
- Monitor Queue Jobs (จำนวน Pending/Failed Jobs)
- Monitor Database Query Performance
- Monitor Server Resources (CPU, Memory, Disk)

**Analytics**:
- Google Analytics สำหรับ Frontend
- Track Custom Events (Lead Submissions, Affiliate Clicks)
- Export Reports สำหรับ Admin Dashboard

🔗 14. Third-party Integrations

**Prop2Share Integration**:
- Scraping จาก Website (ใช้ Browsershot/Playwright)
- หรือใช้ API (ถ้ามี)
- เก็บ Affiliate Links ใน Database

**Google Analytics**:
- ติดตั้ง gtag.js ใน Frontend
- Track Page Views, Lead Submissions, Affiliate Clicks
- สร้าง Custom Events

**OpenAI API**:
- ใช้สำหรับ AI Rewrite Description
- ตั้งค่า API Key ใน `.env`
- ใช้ Rate Limiting เพื่อควบคุม Cost
- Cache Results เพื่อลด API Calls

**Email/SMS Services** (อนาคต):
- SendGrid / Mailgun สำหรับ Email
- Twilio / Nexmo สำหรับ SMS
- ใช้สำหรับ Remarketing และ Notifications

🤖 15. กลยุทธ์ความได้เปรียบ (Competitive Strategy)

**Own Your Data**: 
- การเก็บ Lead ไว้เองทำให้คุณสามารถทำ Remarketing หรือส่ง SMS/Email โปรโมชั่นโครงการอื่นในอนาคตได้
- สร้าง Customer Database สำหรับการตลาดระยะยาว

**SEO Automation**: 
- การดึงข้อมูลมาไว้ใน Domain ของตัวเองและทำ Unique Content ด้วย AI จะช่วยให้เว็บติดอันดับ Google ใน Keyword ชื่อโครงการต่างๆ
- สร้าง Backlinks และ Internal Linking Strategy

**DevOps Scalability**: 
- เนื่องจากคุณรันบน K8s/Proxmox สามารถแยก Worker ของ Scraper ออกจากเว็บหลักเพื่อป้องกันปัญหา Performance เมื่อต้องดึงข้อมูลจำนวนมาก
- Auto-scaling สำหรับ Queue Workers

**Content Uniqueness**:
- AI Rewrite ทำให้เนื้อหาไม่ซ้ำกับเว็บต้นทาง
- ลดความเสี่ยงเรื่อง Duplicate Content จาก Google

⚠️ 16. ข้อควรระวัง (Key Considerations)

**Broken Links**: 
- ต้องมีระบบเช็คว่าทรัพย์ต้นทางยังอยู่ไหม (404 Checker)
- ตั้งค่า Scheduled Job เพื่อตรวจสอบลิงก์เป็นระยะ
- Archive หรือ Hide ทรัพย์สินที่ลิงก์ต้นทางหายไป

**Image Hotlinking**: 
- ควรดึงรูปมาเก็บที่ Server ตัวเองเพื่อป้องกันรูปหายและโหลดเร็วขึ้น
- ใช้ CDN สำหรับ Serve Images (Production)
- Optimize Images เพื่อลด Bandwidth

**Rate Limiting**: 
- การ Scrap ควรมีการหน่วงเวลา (Delay) เพื่อไม่ให้กระทบ Server ต้นทาง
- ใช้ User-Agent ที่เหมาะสม
- หลีกเลี่ยงการ Scrape บ่อยเกินไป

**Legal Considerations**:
- ตรวจสอบ Terms of Service ของเว็บต้นทาง
- ระวังเรื่อง Copyright ของรูปภาพและเนื้อหา
- พิจารณาใช้ API แทน Scraping (ถ้ามี)

**Data Privacy**:
- ปฏิบัติตาม PDPA (Personal Data Protection Act)
- เก็บข้อมูล Lead อย่างปลอดภัย
- ให้ User Consent ก่อนเก็บข้อมูล

**Cost Management**:
- Monitor OpenAI API Usage (มีค่าใช้จ่าย)
- Monitor Server Resources และ Bandwidth
- Optimize เพื่อลด Cost

---

📝 หมายเหตุ

เอกสารนี้สรุปโดยใช้ข้อมูลล่าสุด ณ วันที่ 26 ธันวาคม 2024

**Version**: 1.0  
**Last Updated**: 2024-12-26  
**Maintained By**: Development Team