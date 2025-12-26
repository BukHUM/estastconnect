<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('property');

        // Filter by property
        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from,
                $request->date_to
            ]);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(20);

        return view('admin.leads.index', compact('leads'));
    }

    public function show(Lead $lead)
    {
        $lead->load('property');
        return view('admin.leads.show', compact('lead'));
    }

    public function export(Request $request)
    {
        // TODO: Implement CSV/Excel export
        return response()->json(['message' => 'Export feature coming soon']);
    }
}

