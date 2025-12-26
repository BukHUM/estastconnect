@extends('layouts.admin')

@section('title', 'รายละเอียด Lead')
@section('page-title', 'รายละเอียด Lead')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">ข้อมูล Lead</h1>
        
        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-slate-500">ชื่อ-นามสกุล</dt>
                <dd class="text-lg font-semibold text-slate-900">{{ $lead->name }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-slate-500">เบอร์โทรศัพท์</dt>
                <dd class="text-lg text-slate-900">{{ $lead->phone }}</dd>
            </div>
            
            @if($lead->email)
            <div>
                <dt class="text-sm font-medium text-slate-500">อีเมล</dt>
                <dd class="text-lg text-slate-900">{{ $lead->email }}</dd>
            </div>
            @endif
            
            <div>
                <dt class="text-sm font-medium text-slate-500">ทรัพย์สินที่สนใจ</dt>
                <dd class="text-lg text-slate-900">
                    @if($lead->property)
                        <a href="{{ route('admin.properties.show', $lead->property) }}" class="text-blue-600 hover:text-blue-700">
                            {{ $lead->property->title }}
                        </a>
                    @else
                        -
                    @endif
                </dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-slate-500">วันที่ส่งข้อมูล</dt>
                <dd class="text-lg text-slate-900">{{ $lead->created_at->format('d/m/Y H:i:s') }}</dd>
            </div>
            
            @if($lead->converted_at)
            <div>
                <dt class="text-sm font-medium text-slate-500">วันที่ Convert</dt>
                <dd class="text-lg text-slate-900">{{ $lead->converted_at->format('d/m/Y H:i:s') }}</dd>
            </div>
            @endif
            
            <div>
                <dt class="text-sm font-medium text-slate-500">IP Address</dt>
                <dd class="text-sm text-slate-600">{{ $lead->ip_address }}</dd>
            </div>
            
            @if($lead->notes)
            <div>
                <dt class="text-sm font-medium text-slate-500">หมายเหตุ</dt>
                <dd class="text-sm text-slate-600">{{ $lead->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>
    
    <div class="flex justify-end">
        <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50">
            กลับ
        </a>
    </div>
</div>
@endsection

