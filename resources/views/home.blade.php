@extends('layouts.app')

@section('title', 'PropFinder - ค้นหาโครงการอสังหาริมทรัพย์')
@section('description', 'รวบรวมบ้านและคอนโดทำเลดี พร้อมข้อเสนอสุดพิเศษที่คุณไม่ควรพลาด')

@section('content')
<!-- Hero Section -->
<section class="bg-blue-900 text-white py-12 sm:py-16 lg:py-20 px-4 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-400 via-transparent to-transparent"></div>
    </div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 sm:mb-6 leading-tight">
            ค้นหาโครงการอสังหาฯ <br class="hidden sm:block" />
            ที่ตอบโจทย์ไลฟ์สไตล์คุณ
        </h1>
        <p class="text-blue-100 text-base sm:text-lg mb-8 sm:mb-10 opacity-90">
            รวบรวมบ้านและคอนโดทำเลดี พร้อมข้อเสนอสุดพิเศษที่คุณไม่ควรพลาด
        </p>
        
        <!-- Search Box -->
        <form method="GET" action="{{ route('home') }}" class="bg-white p-2 rounded-2xl shadow-2xl flex flex-col sm:flex-row gap-2 max-w-2xl mx-auto">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="ค้นหาชื่อโครงการ หรือ ทำเล..."
                    class="w-full pl-12 pr-4 py-3 text-slate-800 focus:outline-none rounded-xl"
                />
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 sm:px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                <span>ค้นหา</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </form>
    </div>
</section>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
    <!-- Category Filters -->
    <div class="flex items-center gap-3 sm:gap-4 mb-8 sm:mb-10 overflow-x-auto pb-4 scrollbar-hide -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
        <div class="p-2 sm:p-3 bg-white border border-slate-200 rounded-xl text-slate-400 flex-shrink-0 shadow-sm">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
        </div>
        @php
            $categories = [
                'all' => 'ทั้งหมด',
                'condo' => 'คอนโด',
                'house' => 'บ้านเดี่ยว',
                'land' => 'ที่ดิน'
            ];
            $currentType = request('type', 'all');
        @endphp
        @foreach($categories as $key => $label)
            <a 
                href="{{ route('home', array_merge(request()->except('type'), ['type' => $key === 'all' ? null : $key])) }}"
                class="px-4 sm:px-6 py-2 sm:py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all flex-shrink-0 {{ $currentType === $key || ($key === 'all' && !request('type')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-600 border border-slate-200 hover:border-blue-400' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Property Grid -->
    @if($properties->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-8 sm:mb-12">
            @foreach($properties as $property)
                <div class="group bg-white rounded-2xl sm:rounded-3xl overflow-hidden border border-slate-200 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="relative h-48 sm:h-64 overflow-hidden bg-slate-100">
                        @if($property->mainImage)
                            <img 
                                src="{{ asset('storage/' . $property->mainImage->local_path) }}" 
                                alt="{{ $property->title }}" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                            />
                        @else
                            <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-3 sm:top-4 left-3 sm:left-4 bg-white/90 backdrop-blur-md px-2 sm:px-3 py-1 rounded-full text-xs font-bold text-blue-600 flex items-center gap-1 shadow-sm">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 fill-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <span>แนะนำ</span>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900 leading-tight line-clamp-2 flex-1 min-w-0">{{ $property->title }}</h3>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider flex-shrink-0">
                                @if($property->type === 'condo')
                                    คอนโด
                                @elseif($property->type === 'house')
                                    บ้าน
                                @else
                                    ที่ดิน
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-slate-500 text-xs sm:text-sm mb-3 sm:mb-4 min-w-0">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">{{ $property->location }}</span>
                        </div>
                        <div class="flex items-end justify-between border-t border-slate-100 pt-4 mt-auto gap-3">
                            <div class="min-w-0 flex-1">
                                <span class="text-xs text-slate-400 block mb-1">ราคาเริ่มต้น</span>
                                <span class="text-lg sm:text-xl lg:text-2xl font-black text-blue-600 truncate block">฿{{ number_format($property->price) }}</span>
                            </div>
                            <button 
                                onclick="openLeadModal({{ $property->id }}, '{{ addslashes($property->title) }}')"
                                class="bg-slate-900 text-white p-2 sm:p-3 rounded-xl sm:rounded-2xl hover:bg-blue-600 transition-colors shadow-lg flex-shrink-0 hover:shadow-xl"
                                aria-label="ดูรายละเอียด"
                            >
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $properties->links() }}
        </div>
    @else
        <div class="text-center py-12 sm:py-16">
            <svg class="w-16 h-16 sm:w-20 sm:h-20 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">ไม่พบโครงการที่ค้นหา</h3>
            <p class="text-slate-500 mb-6">ลองเปลี่ยนคำค้นหาหรือตัวกรองดูนะครับ</p>
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                ดูโครงการทั้งหมด
            </a>
        </div>
    @endif
</div>

<!-- Lead Capture Modal -->
@include('partials.lead-modal')

@push('scripts')
<script>
    function openLeadModal(propertyId, propertyTitle) {
        const modal = document.getElementById('lead-modal');
        const propertyIdInput = document.getElementById('lead-property-id');
        const propertyTitleSpan = document.getElementById('lead-property-title');
        
        if (modal && propertyIdInput && propertyTitleSpan) {
            propertyIdInput.value = propertyId;
            propertyTitleSpan.textContent = propertyTitle;
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLeadModal() {
        const modal = document.getElementById('lead-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('lead-modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', closeLeadModal);
        }
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLeadModal();
            }
        });
    });
</script>
@endpush
@endsection

