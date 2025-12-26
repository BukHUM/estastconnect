@extends('layouts.admin')

@section('title', 'รายการทรัพย์สิน')
@section('page-title', 'รายการทรัพย์สิน')

@section('content')
<div class="space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">รอตรวจสอบ</p>
            <p class="text-2xl font-bold">{{ \App\Models\Property::pending()->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">เผยแพร่แล้ว</p>
            <p class="text-2xl font-bold">{{ \App\Models\Property::published()->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">ทั้งหมด</p>
            <p class="text-2xl font-bold">{{ \App\Models\Property::count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">ยอดคลิก</p>
            <p class="text-2xl font-bold">{{ number_format(\App\Models\Property::sum('click_count')) }}</p>
        </div>
    </div>

    <!-- Table Control -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex gap-2">
                <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') === 'pending' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-100' }}">
                    รอตรวจสอบ (Pending)
                </a>
                <a href="{{ route('admin.properties.index', ['status' => 'published']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') === 'published' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-100' }}">
                    ออนไลน์ (Published)
                </a>
                <a href="{{ route('admin.properties.index') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !request('status') ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-100' }}">
                    ทั้งหมด
                </a>
            </div>
            <form method="GET" action="{{ route('admin.properties.index') }}" class="flex gap-2">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาโครงการ..." 
                           class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    ค้นหา
                </button>
            </form>
        </div>

        <!-- Property Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ข้อมูลเบื้องต้น</th>
                        <th class="px-6 py-4">ประเภท/ราคา</th>
                        <th class="px-6 py-4">สถานะ</th>
                        <th class="px-6 py-4">วันที่สร้าง</th>
                        <th class="px-6 py-4">AI Rewrite</th>
                        <th class="px-6 py-4 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($properties as $property)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($property->mainImage)
                                    <img src="{{ asset('storage/' . $property->mainImage->local_path) }}" alt="" class="w-16 h-12 rounded object-cover bg-slate-100">
                                @else
                                    <div class="w-16 h-12 rounded bg-slate-100"></div>
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
                        <td class="px-6 py-4">
                            @if($property->ai_description)
                                <span class="flex items-center gap-1 text-emerald-600 font-medium text-xs bg-emerald-50 px-2 py-1 rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    พร้อมใช้
                                </span>
                            @else
                                <button class="flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium text-xs">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    สั่ง AI เขียนให้
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.properties.show', $property) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.properties.edit', $property) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" class="inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบทรัพย์สินนี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">ไม่พบข้อมูลทรัพย์สิน</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
            <span>แสดง {{ $properties->firstItem() ?? 0 }} - {{ $properties->lastItem() ?? 0 }} จาก {{ $properties->total() }} รายการ</span>
            <div class="flex gap-2 text-slate-600">
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

