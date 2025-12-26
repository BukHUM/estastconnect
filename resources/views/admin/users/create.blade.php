@extends('layouts.admin')

@section('title', 'เพิ่มผู้ใช้')
@section('page-title', 'เพิ่มผู้ใช้')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ชื่อ *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">อีเมล *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">รหัสผ่าน *</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ยืนยันรหัสผ่าน *</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">บทบาท *</label>
                <select name="role" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">เลือกบทบาท</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                    <option value="viewer" {{ old('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50">
                ยกเลิก
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                บันทึก
            </button>
        </div>
    </form>
</div>
@endsection

