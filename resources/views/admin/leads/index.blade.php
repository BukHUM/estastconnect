@extends('layouts.admin')

@section('title', 'Lead Customers')
@section('page-title', 'Lead Customers')

@section('content')
<div class="space-y-8">
    <!-- Filters -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">ค้นหา</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ชื่อ, เบอร์โทร, อีเมล..." 
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">วันที่เริ่มต้น</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">วันที่สิ้นสุด</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    ค้นหา
                </button>
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-slate-900">รายการ Leads</h2>
            <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium">
                Export CSV
            </button>
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
                        <th class="px-6 py-4 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leads as $lead)
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
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-blue-600 hover:text-blue-700 text-sm">ดูรายละเอียด</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">ไม่พบข้อมูล Leads</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
            <span>แสดง {{ $leads->firstItem() ?? 0 }} - {{ $leads->lastItem() ?? 0 }} จาก {{ $leads->total() }} รายการ</span>
            <div class="flex gap-2 text-slate-600">
                {{ $leads->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

