# 🚀 Scraper System - Quick Start Guide

## ✅ สิ่งที่ทำเสร็จแล้ว

- ✅ ScraperService - Logic การ scrape
- ✅ ScrapePropertyJob - Queue Job
- ✅ ScrapeProperties Command - CLI Command
- ✅ ScraperController - Admin Interface
- ✅ Config File - config/scraper.php
- ✅ Scheduled Task - รันอัตโนมัติทุกวัน 02:00 น.

## ⚡ Quick Start

### 1. ตั้งค่า Queue (ถ้ายังไม่ได้ทำ)

```bash
# ถ้าใช้ Database Queue
php artisan queue:table
php artisan migrate

# หรือใช้ Redis (แนะนำ)
# เพิ่มใน .env: QUEUE_CONNECTION=redis
```

### 2. เพิ่ม URLs ใน Config

แก้ไข `config/scraper.php`:

```php
'urls' => [
    'https://example.com/properties',
    // เพิ่ม URLs อื่นๆ
],
```

### 3. ปรับแต่ง Parsing Logic

แก้ไข `app/Services/ScraperService.php` → method `parseProperties()`

ดูรายละเอียดใน: `SCRAPER_IMPLEMENTATION_GUIDE.md`

### 4. รัน Queue Worker

```bash
php artisan queue:work --queue=scraping,default
```

### 5. ทดสอบ Scraper

**วิธีที่ 1: ผ่าน Admin Panel**
- ไปที่ `/admin/scraper`
- คลิก "รัน Scraper"

**วิธีที่ 2: ผ่าน Command**
```bash
php artisan scraper:run
```

## 📋 Checklist

- [ ] ตั้งค่า Queue Connection
- [ ] เพิ่ม URLs ใน config/scraper.php
- [ ] ปรับแต่ง parseProperties() method
- [ ] รัน Queue Worker
- [ ] ทดสอบ Scraping
- [ ] ตรวจสอบ Properties ที่ scrape มา
- [ ] ตั้งค่า Scheduled Task (Production)

## 🔗 เอกสารเพิ่มเติม

- `SCRAPER_SETUP.md` - คู่มือการตั้งค่าแบบละเอียด
- `SCRAPER_IMPLEMENTATION_GUIDE.md` - คู่มือการปรับแต่ง Parsing Logic

---

**Last Updated**: 2024-12-26

