<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('mainImage', 'affiliateLinks')
            ->published();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by province
        if ($request->has('province') && $request->province) {
            $query->where('province', $request->province);
        }

        // Filter by price range
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        $properties = $query->latest()->paginate(12);

        // Get unique provinces for filter
        $provinces = Property::published()
            ->distinct()
            ->pluck('province')
            ->filter()
            ->sort()
            ->values();

        return view('home', compact('properties', 'provinces'));
    }
}

