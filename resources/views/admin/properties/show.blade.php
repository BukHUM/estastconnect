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
            @if($property->ai_description)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">คำอธิบายที่ AI Rewrite</h2>
                <div class="prose max-w-none">
                    <p class="text-slate-700 whitespace-pre-line">{{ $property->ai_description }}</p>
                </div>
            </div>
            @endif
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
@endsection

