<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\OpenAIService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('mainImage', 'affiliateLinks');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'published']);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('external_id', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $properties = $query->latest()->paginate(20);

        return view('admin.properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load(['media', 'affiliateLinks', 'leads']);
        return view('admin.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $property->load(['media', 'affiliateLinks']);
        return view('admin.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:properties,slug,' . $property->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string|in:condo,house,land',
            'location' => 'required|string|max:255',
            'district' => 'nullable|string|max:100',
            'province' => 'required|string|max:100',
            'status' => 'required|in:pending,published,archived',
        ]);

        $property->update($validated);

        return redirect()->route('admin.properties.index')
            ->with('success', 'อัปเดตข้อมูลทรัพย์สินเรียบร้อยแล้ว');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'ลบทรัพย์สินเรียบร้อยแล้ว');
    }

    public function publish(Property $property)
    {
        $property->update([
            'status' => 'published',
            'description' => $property->ai_description ?: $property->description,
        ]);

        return back()->with('success', 'เผยแพร่ทรัพย์สินเรียบร้อยแล้ว');
    }

    public function unpublish(Property $property)
    {
        $property->update(['status' => 'pending']);

        return back()->with('success', 'ยกเลิกการเผยแพร่เรียบร้อยแล้ว');
    }

    /**
     * AI Rewrite description
     */
    public function aiRewrite(Request $request, Property $property, OpenAIService $openAIService)
    {
        if (empty($property->description)) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่มีคำอธิบายให้ Rewrite'
            ], 400);
        }

        $propertyData = [
            'title' => $property->title,
            'location' => $property->location,
            'price' => $property->price,
            'type' => $property->type,
        ];

        $result = $openAIService->rewriteDescription($property->description, $propertyData);

        if ($result['success']) {
            $property->update([
                'ai_description' => $result['description']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'AI Rewrite สำเร็จ',
                'description' => $result['description']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['error']
            ], 500);
        }
    }
}

