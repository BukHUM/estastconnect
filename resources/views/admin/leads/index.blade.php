@extends('layouts.admin')

@section('title', 'Lead Customers')
@section('page-title', 'Lead Customers')

@section('content')
<div class="space-y-8">
    <!-- Filters -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-medium text-slate-700 mb-2">ค้นหา</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ชื่อ, เบอร์โทร, อีเมล..." 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-slate-700 mb-2">วันที่เริ่มต้น</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-slate-700 mb-2">วันที่สิ้นสุด</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors shadow-sm hover:shadow-md">
                    ค้นหา
                </button>
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-lg font-semibold text-slate-900">รายการ Leads</h2>
            <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium transition-colors shadow-sm hover:shadow-md whitespace-nowrap">
                Export CSV
            </button>
        </div>
        <div class="overflow-x-auto -mx-6 sm:mx-0">
            <table class="w-full text-left text-sm min-w-[700px]">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold sticky top-0">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">ชื่อ-นามสกุล</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">เบอร์โทร</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">อีเมล</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">ทรัพย์สินที่สนใจ</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">วันที่</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-right whitespace-nowrap">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 sm:px-6 py-3 sm:py-4 font-medium">{{ $lead->name }}</td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <a href="tel:{{ $lead->phone }}" class="text-blue-600 hover:text-blue-700">{{ $lead->phone }}</a>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 hidden md:table-cell">
                            @if($lead->email)
                                <a href="mailto:{{ $lead->email }}" class="text-blue-600 hover:text-blue-700 truncate block max-w-[200px]">{{ $lead->email }}</a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 min-w-0">
                            @if($lead->property)
                                <a href="{{ route('admin.properties.show', $lead->property) }}" class="text-blue-600 hover:text-blue-700 truncate block max-w-[250px]">
                                    {{ $lead->property->title }}
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-500 text-xs sm:text-sm hidden lg:table-cell">
                            {{ $lead->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-right">
                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium whitespace-nowrap">ดูรายละเอียด</a>
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

