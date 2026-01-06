@extends('layouts.admin')

@section('title', 'รายละเอียดทรัพย์สิน')
@section('page-title', 'รายละเอียดทรัพย์สิน')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-900">{{ $property->title }}</h1>
        <div class="flex gap-2">
            @if($property->status === 'pending')
                <form method="POST" action="{{ route('admin.properties.publish', $property) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium">
                        Publish
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.properties.unpublish', $property) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 text-sm font-medium">
                        Unpublish
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.properties.edit', $property) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                แก้ไข
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Images -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">รูปภาพ</h2>
                <div class="grid grid-cols-2 gap-4">
                    @forelse($property->media as $media)
                        <img src="{{ asset('storage/' . $media->local_path) }}" alt="" class="w-full h-48 object-cover rounded-lg">
                    @empty
                        <p class="text-slate-500">ยังไม่มีรูปภาพ</p>
                    @endforelse
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">คำอธิบาย</h2>
                <div class="prose max-w-none">
                    <p class="text-slate-700 whitespace-pre-line">{{ $property->description }}</p>
                </div>
            </div>

            <!-- AI Description -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">คำอธิบายที่ AI Rewrite</h2>
                    @if(!$property->ai_description)
                        <button 
                            onclick="aiRewrite({{ $property->id }})"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2 ai-rewrite-btn"
                            data-property-id="{{ $property->id }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            <span class="ai-rewrite-text">สั่ง AI เขียนให้</span>
                            <span class="ai-rewrite-loading hidden">กำลังประมวลผล...</span>
                        </button>
                    @endif
                </div>
                <div class="prose max-w-none">
                    @if($property->ai_description)
                        <p class="text-slate-700 whitespace-pre-line" id="ai-description-content">{{ $property->ai_description }}</p>
                    @else
                        <p class="text-slate-400 italic">ยังไม่มีคำอธิบายที่ AI Rewrite คลิกปุ่มด้านบนเพื่อสร้าง</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Property Info -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">ข้อมูลทรัพย์สิน</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-slate-500">ประเภท</dt>
                        <dd class="text-sm font-medium">{{ $property->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">ราคา</dt>
                        <dd class="text-sm font-medium text-blue-600">฿{{ number_format($property->price) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">ที่ตั้ง</dt>
                        <dd class="text-sm font-medium">{{ $property->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">สถานะ</dt>
                        <dd class="text-sm font-medium">
                            @if($property->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">รอตรวจ</span>
                            @elseif($property->status === 'published')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">ออนไลน์</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">ผิดพลาด</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Affiliate Links -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Affiliate Links</h2>
                <div class="space-y-2">
                    @forelse($property->affiliateLinks as $link)
                        <a href="{{ $link->link_url }}" target="_blank" class="block text-blue-600 hover:text-blue-700 text-sm">
                            {{ $link->provider }} ({{ $link->click_count }} clicks)
                        </a>
                    @empty
                        <p class="text-slate-500 text-sm">ยังไม่มี Affiliate Links</p>
                    @endforelse
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">สถิติ</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-slate-500">จำนวนการดู</dt>
                        <dd class="text-sm font-medium">{{ number_format($property->view_count) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">จำนวนการคลิก</dt>
                        <dd class="text-sm font-medium">{{ number_format($property->click_count) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">จำนวน Leads</dt>
                        <dd class="text-sm font-medium">{{ $property->leads->count() }}</dd>
                    </div>
                </dl>
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
    const contentDiv = document.getElementById('ai-description-content');
    
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
            // Update content
            if (contentDiv) {
                contentDiv.textContent = data.description;
            } else {
                // Create content if doesn't exist
                const proseDiv = document.querySelector('.prose');
                if (proseDiv) {
                    proseDiv.innerHTML = `<p class="text-slate-700 whitespace-pre-line" id="ai-description-content">${data.description}</p>`;
                }
            }
            
            // Hide button
            btn.style.display = 'none';
            
            showNotification('success', data.message || 'AI Rewrite สำเร็จ');
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

