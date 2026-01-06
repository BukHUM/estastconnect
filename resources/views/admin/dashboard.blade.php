@extends('layouts.admin')

@section('title', 'ภาพรวมระบบ')
@section('page-title', 'ภาพรวมระบบ')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900">Dashboard</h2>
        <p class="text-slate-600 mt-2">ยินดีต้อนรับ, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-xl p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div class="ml-4 min-w-0 flex-1">
                    <p class="text-xs sm:text-sm font-medium text-slate-600 truncate">อสังหาริมทรัพย์</p>
                    <p class="text-xl sm:text-2xl font-semibold text-slate-900 mt-1">{{ $stats['published_properties'] + $stats['pending_properties'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-xl p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4 min-w-0 flex-1">
                    <p class="text-xs sm:text-sm font-medium text-slate-600 truncate">ลูกค้า</p>
                    <p class="text-xl sm:text-2xl font-semibold text-slate-900 mt-1">{{ $stats['total_leads'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 sm:p-6 hover:shadow-md transition-shadow sm:col-span-2 lg:col-span-1">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-xl p-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="ml-4 min-w-0 flex-1">
                    <p class="text-xs sm:text-sm font-medium text-slate-600 truncate">เอกสาร</p>
                    <p class="text-xl sm:text-2xl font-semibold text-slate-900 mt-1">{{ $stats['failed_scrapes'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="min-w-0 flex-1">
                <p class="text-xs sm:text-sm text-slate-500 mb-1 truncate">รอตรวจสอบ</p>
                <p class="text-xl sm:text-2xl font-bold text-slate-900">{{ $stats['pending_properties'] }}</p>
            </div>
            <div class="p-2 sm:p-3 rounded-lg bg-amber-50 flex-shrink-0 ml-3">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="min-w-0 flex-1">
                <p class="text-xs sm:text-sm text-slate-500 mb-1 truncate">เผยแพร่แล้ว</p>
                <p class="text-xl sm:text-2xl font-bold text-slate-900">{{ $stats['published_properties'] }}</p>
            </div>
            <div class="p-2 sm:p-3 rounded-lg bg-emerald-50 flex-shrink-0 ml-3">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="min-w-0 flex-1">
                <p class="text-xs sm:text-sm text-slate-500 mb-1 truncate">Scrap พลาด</p>
                <p class="text-xl sm:text-2xl font-bold text-slate-900">{{ $stats['failed_scrapes'] }}</p>
            </div>
            <div class="p-2 sm:p-3 rounded-lg bg-red-50 flex-shrink-0 ml-3">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="min-w-0 flex-1">
                <p class="text-xs sm:text-sm text-slate-500 mb-1 truncate">ยอดคลิก Affiliate</p>
                <p class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['total_clicks']) }}</p>
            </div>
            <div class="p-2 sm:p-3 rounded-lg bg-blue-50 flex-shrink-0 ml-3">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Recent Properties -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">ทรัพย์สินล่าสุด</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ข้อมูลเบื้องต้น</th>
                        <th class="px-6 py-4">ประเภท/ราคา</th>
                        <th class="px-6 py-4">สถานะ</th>
                        <th class="px-6 py-4">วันที่สร้าง</th>
                        <th class="px-6 py-4 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentProperties as $property)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($property->mainImage)
                                    <img src="{{ asset('storage/' . $property->mainImage->local_path) }}" alt="" class="w-16 h-12 rounded object-cover bg-slate-100">
                                @else
                                    <div class="w-16 h-12 rounded bg-slate-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $property->title }}</div>
                                    <div class="text-xs text-slate-400">ID: {{ $property->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $property->type }}</div>
                            <div class="font-medium text-blue-600">฿{{ number_format($property->price) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($property->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">รอตรวจ</span>
                            @elseif($property->status === 'published')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">ออนไลน์</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">ผิดพลาด</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $property->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.properties.show', $property) }}" class="text-blue-600 hover:text-blue-700 text-sm">ดูรายละเอียด</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">ยังไม่มีข้อมูลทรัพย์สิน</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Leads -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Leads ล่าสุด</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-4">เบอร์โทร</th>
                        <th class="px-6 py-4">อีเมล</th>
                        <th class="px-6 py-4">ทรัพย์สินที่สนใจ</th>
                        <th class="px-6 py-4">วันที่</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentLeads as $lead)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-medium">{{ $lead->name }}</td>
                        <td class="px-6 py-4">{{ $lead->phone }}</td>
                        <td class="px-6 py-4">{{ $lead->email ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($lead->property)
                                <a href="{{ route('admin.properties.show', $lead->property) }}" class="text-blue-600 hover:text-blue-700">
                                    {{ $lead->property->title }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">ยังไม่มีข้อมูล Leads</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- System Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold text-slate-800 mb-4">ภาพรวมระบบ</h3>
        <p class="text-slate-600">ระบบจัดการอสังหาริมทรัพย์ Estate Connect พร้อมใช้งานแล้ว</p>
    </div>
</div>
@endsection

