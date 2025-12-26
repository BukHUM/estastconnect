@extends('layouts.admin')

@section('title', 'จัดการผู้ใช้')
@section('page-title', 'จัดการผู้ใช้')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-900">รายการผู้ใช้</h2>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            + เพิ่มผู้ใช้
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ชื่อ</th>
                        <th class="px-6 py-4">อีเมล</th>
                        <th class="px-6 py-4">บทบาท</th>
                        <th class="px-6 py-4">วันที่สร้าง</th>
                        <th class="px-6 py-4 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">Admin</span>
                            @elseif($user->role === 'editor')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Editor</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">Viewer</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">ไม่พบข้อมูลผู้ใช้</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
            <span>แสดง {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} จาก {{ $users->total() }} รายการ</span>
            <div class="flex gap-2 text-slate-600">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

