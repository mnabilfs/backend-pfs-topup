<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    // GET /api/banners - Ambil semua banner aktif (untuk Home)
    public function index()
    {
        return Banner::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    // GET /api/banners/all - Ambil semua banner (untuk Dashboard Admin)
    public function all()
    {
        return Banner::orderBy('order')->get();
    }

    // POST /api/banners
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image_url' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $banner = Banner::create($validated);

        return response()->json($banner, 201);
    }

    // GET /api/banners/{id}
    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    // PUT /api/banners/{id}
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image_url' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $banner->update($validated);

        return response()->json($banner);
    }

    // DELETE /api/banners/{id}
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
