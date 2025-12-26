@extends('layouts.admin')

@section('title', 'แก้ไขทรัพย์สิน')
@section('page-title', 'แก้ไขทรัพย์สิน')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.properties.update', $property) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ชื่อโครงการ *</label>
                <input type="text" name="title" value="{{ old('title', $property->title) }}" required
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $property->slug) }}"
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">คำอธิบาย</label>
                <textarea name="description" rows="5"
                          class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $property->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ราคา *</label>
                    <input type="number" name="price" value="{{ old('price', $property->price) }}" required step="0.01"
                           class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ประเภท *</label>
                    <select name="type" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="condo" {{ old('type', $property->type) === 'condo' ? 'selected' : '' }}>คอนโด</option>
                        <option value="house" {{ old('type', $property->type) === 'house' ? 'selected' : '' }}>บ้าน</option>
                        <option value="land" {{ old('type', $property->type) === 'land' ? 'selected' : '' }}>ที่ดิน</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ที่ตั้ง *</label>
                <input type="text" name="location" value="{{ old('location', $property->location) }}" required
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">เขต/อำเภอ</label>
                    <input type="text" name="district" value="{{ old('district', $property->district) }}"
                           class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">จังหวัด *</label>
                    <input type="text" name="province" value="{{ old('province', $property->province) }}" required
                           class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">สถานะ *</label>
                <select name="status" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pending" {{ old('status', $property->status) === 'pending' ? 'selected' : '' }}>รอตรวจสอบ</option>
                    <option value="published" {{ old('status', $property->status) === 'published' ? 'selected' : '' }}>เผยแพร่แล้ว</option>
                    <option value="archived" {{ old('status', $property->status) === 'archived' ? 'selected' : '' }}>เก็บไว้</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50">
                ยกเลิก
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                บันทึก
            </button>
        </div>
    </form>
</div>
@endsection

