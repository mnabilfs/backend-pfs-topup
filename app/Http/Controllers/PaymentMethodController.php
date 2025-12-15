<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    // GET semua payment methods (untuk admin)
    public function index()
    {
        $methods = PaymentMethod::ordered()->get();
        return response()->json($methods);
    }

    // GET payment methods aktif (untuk public)
    public function getActive()
    {
        $methods = PaymentMethod::active()->ordered()->get();
        return response()->json($methods);
    }

    // POST - Tambah payment method baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_url' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $method = PaymentMethod::create($validated);

        return response()->json([
            'message' => 'Payment method created successfully',
            'data' => $method
        ], 201);
    }

    // GET detail payment method
    public function show($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return response()->json($method);
    }

    // PUT - Update payment method
    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'image_url' => 'sometimes|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $method->update($validated);

        return response()->json([
            'message' => 'Payment method updated successfully',
            'data' => $method
        ]);
    }

    // DELETE - Hapus payment method
    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->delete();

        return response()->json([
            'message' => 'Payment method deleted successfully'
        ]);
    }
}
