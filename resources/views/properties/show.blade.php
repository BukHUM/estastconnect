@extends('layouts.app')

@section('title', $property->title . ' - PropFinder')
@section('description', Str::limit($property->ai_description ?: $property->description, 160))
@section('og_title', $property->title)
@section('og_description', Str::limit($property->ai_description ?: $property->description, 160))
@section('og_image', $property->mainImage ? asset('storage/' . $property->mainImage->local_path) : asset('favicon.ico'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-slate-500">
        <ol class="flex items-center gap-2">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">หน้าแรก</a></li>
            <li>/</li>
            <li><a href="{{ route('home', ['type' => $property->type]) }}" class="hover:text-blue-600">
                @if($property->type === 'condo')
                    คอนโด
                @elseif($property->type === 'house')
                    บ้าน
                @else
                    ที่ดิน
                @endif
            </a></li>
            <li>/</li>
            <li class="text-slate-900">{{ $property->title }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">
            <!-- Images Gallery -->
            <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                @if($property->media->count() > 0)
                    <div class="relative">
                        <!-- Main Image -->
                        <div class="aspect-video overflow-hidden bg-slate-200">
                            <img 
                                id="main-image"
                                src="{{ asset('storage/' . $property->media->first()->local_path) }}" 
                                alt="{{ $property->title }}" 
                                class="w-full h-full object-cover"
                            />
                        </div>
                        
                        <!-- Thumbnail Gallery -->
                        @if($property->media->count() > 1)
                            <div class="p-4 grid grid-cols-4 sm:grid-cols-6 gap-2 sm:gap-4">
                                @foreach($property->media as $media)
                                    <button 
                                        onclick="changeMainImage('{{ asset('storage/' . $media->local_path) }}')"
                                        class="aspect-video overflow-hidden rounded-lg border-2 border-transparent hover:border-blue-500 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <img 
                                            src="{{ asset('storage/' . $media->local_path) }}" 
                                            alt="{{ $property->title }}" 
                                            class="w-full h-full object-cover"
                                        />
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="aspect-video bg-slate-200 flex items-center justify-center">
                        <svg class="w-20 h-20 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Property Info -->
            <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">{{ $property->title }}</h1>
                
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm sm:text-base text-slate-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $property->location }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>
                            @if($property->type === 'condo')
                                คอนโด
                            @elseif($property->type === 'house')
                                บ้านเดี่ยว
                            @else
                                ที่ดิน
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="prose max-w-none mb-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">รายละเอียดโครงการ</h2>
                    <div class="text-slate-700 whitespace-pre-line leading-relaxed">
                        {{ $property->ai_description ?: $property->description }}
                    </div>
                </div>

                <!-- Features -->
                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">ข้อมูลโครงการ</h2>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-slate-500 mb-1">ราคาเริ่มต้น</dt>
                            <dd class="text-lg sm:text-xl font-bold text-blue-600">฿{{ number_format($property->price) }}</dd>
                        </div>
                        @if($property->district)
                        <div>
                            <dt class="text-sm text-slate-500 mb-1">เขต/อำเภอ</dt>
                            <dd class="text-base font-medium text-slate-900">{{ $property->district }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm text-slate-500 mb-1">จังหวัด</dt>
                            <dd class="text-base font-medium text-slate-900">{{ $property->province }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500 mb-1">จำนวนการดู</dt>
                            <dd class="text-base font-medium text-slate-900">{{ number_format($property->view_count) }} ครั้ง</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Related Properties -->
            @if($relatedProperties->count() > 0)
            <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-6">โครงการที่เกี่ยวข้อง</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    @foreach($relatedProperties as $related)
                        <a href="{{ route('properties.show', $related->slug) }}" class="group block">
                            <div class="bg-white rounded-xl overflow-hidden border border-slate-200 hover:shadow-lg transition-all">
                                <div class="aspect-video overflow-hidden bg-slate-200">
                                    @if($related->mainImage)
                                        <img 
                                            src="{{ asset('storage/' . $related->mainImage->local_path) }}" 
                                            alt="{{ $related->title }}" 
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                        />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-slate-900 mb-2 line-clamp-2">{{ $related->title }}</h3>
                                    <p class="text-sm text-slate-500 mb-2">{{ $related->location }}</p>
                                    <p class="text-lg font-bold text-blue-600">฿{{ number_format($related->price) }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- CTA Card -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-white shadow-xl sticky top-24">
                <div class="text-center mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold mb-2">สนใจโครงการนี้?</h3>
                    <p class="text-blue-100 text-sm sm:text-base">กรอกข้อมูลเพื่อรับข้อเสนอพิเศษและดูรายละเอียดเพิ่มเติม</p>
                </div>
                
                <button 
                    onclick="openLeadModal({{ $property->id }}, '{{ $property->title }}')"
                    class="w-full bg-white text-blue-600 py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg hover:bg-blue-50 transition-all shadow-lg mb-4"
                >
                    รับข้อมูลโครงการ
                </button>
                
                <div class="text-center text-xs sm:text-sm text-blue-100">
                    <p>หรือโทรหาเรา</p>
                    <a href="tel:02-xxx-xxxx" class="text-white font-bold text-base sm:text-lg hover:underline">
                        02-xxx-xxxx
                    </a>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="bg-white rounded-2xl sm:rounded-3xl p-6 border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-900 mb-4">ข้อมูลด่วน</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">ราคาเริ่มต้น</dt>
                        <dd class="font-bold text-blue-600">฿{{ number_format($property->price) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">ประเภท</dt>
                        <dd class="font-medium">
                            @if($property->type === 'condo')
                                คอนโด
                            @elseif($property->type === 'house')
                                บ้านเดี่ยว
                            @else
                                ที่ดิน
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">จังหวัด</dt>
                        <dd class="font-medium">{{ $property->province }}</dd>
                    </div>
                    @if($property->district)
                    <div class="flex justify-between">
                        <dt class="text-slate-500">เขต/อำเภอ</dt>
                        <dd class="font-medium">{{ $property->district }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Lead Capture Modal -->
@include('partials.lead-modal')

@push('scripts')
<script>
    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById('main-image');
        if (mainImage) {
            mainImage.src = imageSrc;
        }
    }

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

