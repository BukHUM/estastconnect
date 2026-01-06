@extends('layouts.admin')

@section('title', 'จัดการ Scraper')
@section('page-title', 'จัดการ Scraper')

@section('content')
<div class="space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">Total Runs</p>
            <p class="text-2xl font-bold">{{ $stats['total_runs'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">Success Rate</p>
            <p class="text-2xl font-bold">{{ $stats['success_rate'] }}%</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">Error Rate</p>
            <p class="text-2xl font-bold">{{ $stats['error_rate'] }}%</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">Recent Failed (7 days)</p>
            <p class="text-2xl font-bold">{{ $stats['recent_failed'] }}</p>
        </div>
    </div>

    <!-- Run Scraper Form -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sm:p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">รัน Scraper</h2>
        <form method="POST" action="{{ route('admin.scraper.run') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="flex-1">
                <input 
                    type="url" 
                    name="url" 
                    placeholder="URL ที่จะ Scrape (เว้นว่างเพื่อรันทั้งหมด)" 
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm"
                >
                <p class="text-xs text-slate-500 mt-1">ถ้าเว้นว่าง ระบบจะ scrape URLs ทั้งหมดที่ตั้งค่าไว้ใน config/scraper.php</p>
            </div>
            <button 
                type="submit" 
                class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center justify-center gap-2 transition-colors shadow-sm hover:shadow-md whitespace-nowrap"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                รัน Scraper
            </button>
        </form>
    </div>

    <!-- Scraper Logs Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="p-4 sm:p-6 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Scraping Logs</h2>
        </div>
        <div class="overflow-x-auto -mx-6 sm:mx-0">
            <table class="w-full text-left text-sm min-w-[800px]">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold sticky top-0">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">URL</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">สถานะ</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">ทรัพย์ที่พบ</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">ทรัพย์ที่บันทึก</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">เวลาที่ใช้</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">วันที่ Scrape</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">Error</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <a href="{{ $log->url }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-xs sm:text-sm truncate block max-w-xs" title="{{ $log->url }}">
                                {{ $log->url }}
                            </a>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            @if($log->status === 'success')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 whitespace-nowrap">สำเร็จ</span>
                            @elseif($log->status === 'failed')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 whitespace-nowrap">ล้มเหลว</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 whitespace-nowrap">บางส่วน</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 font-medium">{{ $log->properties_found ?? 0 }}</td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 font-medium text-emerald-600">{{ $log->properties_saved ?? 0 }}</td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-500 hidden md:table-cell">{{ $log->execution_time ?? 0 }}s</td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-500 text-xs sm:text-sm hidden lg:table-cell">
                            {{ $log->last_scraped_at ? $log->last_scraped_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 min-w-0">
                            @if($log->error_message)
                                <span class="text-xs text-red-600 truncate block max-w-[200px]" title="{{ $log->error_message }}">
                                    {{ Str::limit($log->error_message, 50) }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">ยังไม่มี Logs</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
            <span>แสดง {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} จาก {{ $logs->total() }} รายการ</span>
            <div class="flex gap-2 text-slate-600">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

