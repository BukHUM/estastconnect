<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function show($slug)
    {
        $property = Property::with(['media', 'affiliateLinks'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count
        $property->increment('view_count');

        // Get related properties
        $relatedProperties = Property::with('mainImage')
            ->published()
            ->where('id', '!=', $property->id)
            ->where(function($q) use ($property) {
                $q->where('type', $property->type)
                  ->orWhere('province', $property->province);
            })
            ->limit(4)
            ->get();

        return view('properties.show', compact('property', 'relatedProperties'));
    }
}

