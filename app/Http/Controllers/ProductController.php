<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products
    public function index()
    {
        return Product::all();
    }

    // POST /api/products
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image_url' => 'nullable|string'
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $product = Product::with('game')->findOrFail($id);
        return response()->json($product);
    }

    // PUT /api/products/{id}
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image_url' => 'nullable|string'
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    // DELETE /api/products/{id}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
