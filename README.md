# PropFinder - Real Estate Affiliate Platform

> ระบบจัดการและแสดงผลข้อมูลอสังหาริมทรัพย์ พร้อมระบบ Lead Generation และ Affiliate Marketing

## 📋 เกี่ยวกับโปรเจกต์

**PropFinder** (หรือ **Estate Connect**) เป็นแพลตฟอร์มสำหรับรวบรวมและจัดการข้อมูลอสังหาริมทรัพย์ โดยมีฟีเจอร์หลักดังนี้:

- 🔍 **Scraping Engine**: ดึงข้อมูลอสังหาริมทรัพย์จากเว็บต้นทางอัตโนมัติ
- 🤖 **AI Content Enhancement**: ใช้ OpenAI API เพื่อปรับปรุงและ Rewrite คำอธิบายทรัพย์สิน
- 🏠 **Property Portal**: หน้าเว็บสำหรับผู้ใช้ทั่วไปค้นหาและดูรายละเอียดโครงการ
- 📊 **Admin Dashboard**: ระบบจัดการหลังบ้านสำหรับตรวจสอบ แก้ไข และ Publish ทรัพย์สิน
- 👥 **Lead Generation**: ระบบเก็บข้อมูลลูกค้าที่สนใจและส่งต่อไปยัง Affiliate Links
- 📈 **Analytics & Tracking**: ติดตามสถิติการดู คลิก และ Leads

## 🛠️ เทคโนโลยีที่ใช้

### Backend
- **Framework**: Laravel 12+ (PHP 8.2+)
- **Database**: MySQL 8.0+
- **Queue System**: Redis / Database Queue
- **Cache**: Redis / Memcached

### Frontend
- **Templates**: Laravel Blade
- **CSS Framework**: Tailwind CSS 4.0
- **JavaScript**: Alpine.js 3.x
- **Icons**: SVG Icons

### Admin Panel
- **Custom Development**: Laravel Blade + Tailwind CSS + Alpine.js
- **Responsive Design**: รองรับ Mobile, Tablet, Desktop

### Integrations
- **AI**: OpenAI API (GPT-4o) สำหรับ Rewrite Content
- **Scraping**: Spatie/Browsershot หรือ Playwright
- **Analytics**: Google Analytics

## 🚀 ฟีเจอร์หลัก

### สำหรับผู้ใช้ทั่วไป (Frontend)
- ✅ ค้นหาโครงการอสังหาริมทรัพย์ (บ้าน, คอนโด, ที่ดิน)
- ✅ ดูรายละเอียดโครงการพร้อมรูปภาพ
- ✅ กรองตามประเภท ราคา และทำเล
- ✅ กรอกข้อมูลสนใจ (Lead Capture) เพื่อรับข้อมูลเพิ่มเติม
- ✅ SEO Optimized สำหรับการค้นหาใน Google

### สำหรับ Admin (Backend)
- ✅ **Dashboard**: ภาพรวมสถิติระบบ
- ✅ **Property Management**: จัดการทรัพย์สิน (เพิ่ม, แก้ไข, Publish, ลบ)
- ✅ **AI Rewrite**: ใช้ AI ปรับปรุงคำอธิบายทรัพย์สิน
- ✅ **Lead Management**: ดูและ Export ข้อมูล Leads
- ✅ **Scraper Management**: จัดการและรัน Scraper
- ✅ **User Management**: จัดการผู้ใช้และสิทธิ์ (Admin, Editor, Viewer)

## 📦 การติดตั้ง

### ความต้องการของระบบ
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM
- Redis (แนะนำ) หรือ Database Queue

### ขั้นตอนการติดตั้ง

1. **Clone repository**
```bash
git clone <repository-url>
cd estateconnect
```

2. **ติดตั้ง Dependencies**
```bash
composer install
npm install
```

3. **ตั้งค่า Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **ตั้งค่าฐานข้อมูลใน `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=estateconnect
DB_USERNAME=root
DB_PASSWORD=
```

5. **รัน Migration และ Seeder**
```bash
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
```

6. **Build Assets**
```bash
npm run build
# หรือสำหรับ development
npm run dev
```

7. **สร้าง Storage Link**
```bash
php artisan storage:link
```

8. **รัน Queue Worker** (ถ้าใช้ Queue)
```bash
php artisan queue:work
```

## 👤 ข้อมูล Admin User

หลังจากรัน seeder แล้ว คุณสามารถ login ด้วย:

- **Email**: `admin@example.com`
- **Password**: `password`
- **URL**: `http://localhost/estateconnect/login`

> ⚠️ **คำเตือน**: ควรเปลี่ยนรหัสผ่าน default ทันทีหลังการติดตั้ง

## 📁 โครงสร้างโปรเจกต์

```
estateconnect/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin Controllers
│   │   │   ├── Auth/           # Authentication
│   │   │   └── ...             # Frontend Controllers
│   │   └── Middleware/          # Custom Middleware
│   └── Models/                  # Eloquent Models
├── database/
│   ├── migrations/              # Database Migrations
│   └── seeders/                 # Database Seeders
├── resources/
│   ├── views/
│   │   ├── admin/               # Admin Panel Views
│   │   ├── layouts/             # Layout Templates
│   │   └── ...                  # Frontend Views
│   ├── css/                     # Tailwind CSS
│   └── js/                      # JavaScript
├── routes/
│   └── web.php                  # Web Routes
└── storage/                      # File Storage
```

## 🔐 Authentication & Authorization

ระบบใช้ Role-Based Access Control (RBAC):

- **Admin**: สิทธิ์เต็ม (จัดการทุกอย่าง)
- **Editor**: แก้ไขและ Publish ทรัพย์สิน
- **Viewer**: ดูข้อมูลเท่านั้น

Admin routes ถูกป้องกันด้วย middleware `role:admin`

## 📊 Database Schema

### ตารางหลัก
- `properties` - ข้อมูลทรัพย์สิน
- `media` - รูปภาพทรัพย์สิน
- `affiliate_links` - ลิงก์ Affiliate
- `leads` - ข้อมูลลูกค้าที่สนใจ
- `scraper_logs` - Log การ Scrape
- `users` - ผู้ใช้ระบบ

## 🔄 Workflow

1. **Scraping**: ระบบดึงข้อมูลจากเว็บต้นทาง → เก็บในฐานข้อมูล (status: pending)
2. **Review**: Admin ตรวจสอบและแก้ไขข้อมูล
3. **AI Enhancement**: ใช้ AI Rewrite คำอธิบาย (ถ้าต้องการ)
4. **Publishing**: Admin Publish ทรัพย์สิน → แสดงในหน้าเว็บ
5. **Lead Generation**: ผู้ใช้สนใจ → กรอกข้อมูล → ส่งต่อไปยัง Affiliate Link

## 🧪 Development

### รัน Development Server
```bash
php artisan serve
npm run dev
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📝 License

This project is proprietary software. All rights reserved.

## 📞 Support

สำหรับคำถามหรือปัญหาการใช้งาน กรุณาติดต่อทีมพัฒนา

---

**Version**: 1.0  
**Last Updated**: 2024-12-26
