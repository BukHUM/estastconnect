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
        <div class="overflow-x-auto -mx-6 sm:mx-0">
            <table class="w-full text-left text-sm min-w-[800px]">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold sticky top-0">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">ข้อมูลเบื้องต้น</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">ประเภท/ราคา</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">สถานะ</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">วันที่สร้าง</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">AI Rewrite</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-right whitespace-nowrap">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($properties as $property)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                @if($property->mainImage)
                                    <img src="{{ asset('storage/' . $property->mainImage->local_path) }}" alt="" class="w-12 h-10 sm:w-16 sm:h-12 rounded object-cover bg-slate-100 flex-shrink-0">
                                @else
                                    <div class="w-12 h-10 sm:w-16 sm:h-12 rounded bg-slate-100 flex-shrink-0 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-slate-900 truncate">{{ $property->title }}</div>
                                    <div class="text-xs text-slate-400">ID: {{ $property->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="text-xs sm:text-sm text-slate-600 mb-1">{{ $property->type }}</div>
                            <div class="font-medium text-blue-600 text-sm sm:text-base">฿{{ number_format($property->price) }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            @if($property->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 whitespace-nowrap">รอตรวจ</span>
                            @elseif($property->status === 'published')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 whitespace-nowrap">ออนไลน์</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 whitespace-nowrap">ผิดพลาด</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-500 text-xs sm:text-sm hidden md:table-cell">
                            {{ $property->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">
                            @if($property->ai_description)
                                <span class="flex items-center gap-1 text-emerald-600 font-medium text-xs bg-emerald-50 px-2 py-1 rounded whitespace-nowrap">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    พร้อมใช้
                                </span>
                            @else
                                <button 
                                    onclick="aiRewrite({{ $property->id }})"
                                    class="flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium text-xs whitespace-nowrap ai-rewrite-btn"
                                    data-property-id="{{ $property->id }}"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    <span class="ai-rewrite-text">สั่ง AI เขียนให้</span>
                                    <span class="ai-rewrite-loading hidden">กำลังประมวลผล...</span>
                                </button>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-right">
                            <div class="flex justify-end gap-1 sm:gap-2">
                                <a href="{{ route('admin.properties.show', $property) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all" title="ดูรายละเอียด">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.properties.edit', $property) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all" title="แก้ไข">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" class="inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบทรัพย์สินนี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all" title="ลบ">
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

@push('scripts')
<script>
function aiRewrite(propertyId) {
    const btn = document.querySelector(`[data-property-id="${propertyId}"]`);
    const textSpan = btn?.querySelector('.ai-rewrite-text');
    const loadingSpan = btn?.querySelector('.ai-rewrite-loading');
    
    if (!btn) return;
    
    // Disable button and show loading
    btn.disabled = true;
    if (textSpan) textSpan.classList.add('hidden');
    if (loadingSpan) loadingSpan.classList.remove('hidden');
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    
    fetch(`/admin/properties/${propertyId}/ai-rewrite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('success', data.message || 'AI Rewrite สำเร็จ');
            
            // Reload page after 1 second
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'เกิดข้อผิดพลาด');
            // Re-enable button
            btn.disabled = false;
            if (textSpan) textSpan.classList.remove('hidden');
            if (loadingSpan) loadingSpan.classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
        // Re-enable button
        btn.disabled = false;
        if (textSpan) textSpan.classList.remove('hidden');
        if (loadingSpan) loadingSpan.classList.add('hidden');
    });
}

function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-emerald-50 border-l-4 border-emerald-400 text-emerald-700' : 'bg-red-50 border-l-4 border-red-400 text-red-700'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success' 
                    ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                    : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush
@endsection

