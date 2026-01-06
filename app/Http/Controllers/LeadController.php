<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Property;
use App\Models\AffiliateLink;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9]{9,10}$/',
            'email' => 'nullable|email|max:255',
        ]);

        // Get property
        $property = Property::findOrFail($validated['property_id']);

        // Create lead
        $lead = Lead::create([
            'property_id' => $validated['property_id'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'source' => 'website',
            'converted_at' => now(),
        ]);

        // Get affiliate link
        $affiliateLink = AffiliateLink::where('property_id', $property->id)
            ->where('is_active', true)
            ->first();

        if ($affiliateLink) {
            // Increment click count
            $affiliateLink->increment('click_count');
            $affiliateLink->update(['last_clicked_at' => now()]);
            
            // Increment property click count
            $property->increment('click_count');

            // Track Google Analytics event (if gtag is available)
            $redirectUrl = $affiliateLink->link_url;
        } else {
            // Fallback if no affiliate link
            $redirectUrl = '#';
        }

        // Return JSON response for AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว',
                'redirect_url' => $redirectUrl,
            ]);
        }

        // Redirect for non-AJAX requests
        return redirect($redirectUrl)
            ->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }
}

