<?php

namespace App\Http\Controllers;

use App\Models\SoldAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoldAccountController extends Controller
{
    // Untuk admin dashboard – tampilkan semua
    public function all()
    {
        try {
            $accounts = SoldAccount::orderBy('order')->get();
            return response()->json($accounts);
        } catch (\Exception $e) {
            Log::error('Error fetching all sold accounts: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal memuat data akun',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Untuk halaman depan (opsional) – hanya yang aktif
// Method index() - untuk public (hanya yang aktif) DAN admin (semua data)
public function index()
{
    try {
        // Cek apakah request dari admin yang terautentikasi
        if (auth('sanctum')->check() && auth('sanctum')->user()->role === 'admin') {
            // Admin: tampilkan SEMUA akun (aktif & nonaktif)
            $accounts = SoldAccount::orderBy('order')->get();
        } else {
            // Public: hanya akun aktif
            $accounts = SoldAccount::where('is_active', true)
                                   ->orderBy('order')
                                   ->get();
        }

        return response()->json($accounts);
    } catch (\Exception $e) {
        Log::error('Error fetching sold accounts: ' . $e->getMessage());
        return response()->json([
            'error' => 'Gagal memuat data akun',
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'required|integer|min:0',
                'image_url'   => 'required|string',
                'gallery'     => 'nullable|array',
                'gallery.*'   => 'string',
                'order'       => 'nullable|integer',
                'is_active'   => 'nullable|boolean',
            ]);

            $account = SoldAccount::create($validated);

            Log::info('Sold account created: ' . $account->id);

            return response()->json($account, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating sold account: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal menyimpan akun',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $account = SoldAccount::findOrFail($id);
            return response()->json($account);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Akun tidak ditemukan',
                'id' => $id
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching sold account ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal memuat akun',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $account = SoldAccount::findOrFail($id);

            $validated = $request->validate([
                'title'       => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'sometimes|required|integer|min:0',
                'image_url'   => 'sometimes|required|string',
                'gallery'     => 'nullable|array',
                'gallery.*'   => 'string',
                'order'       => 'nullable|integer',
                'is_active'   => 'nullable|boolean',
            ]);

            $account->update($validated);

            Log::info('Sold account updated: ' . $account->id);

            return response()->json($account);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Akun tidak ditemukan',
                'id' => $id
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating sold account ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal mengupdate akun',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $account = SoldAccount::findOrFail($id);
            $account->delete();

            Log::info('Sold account deleted: ' . $id);

            return response()->json(['message' => 'Akun berhasil dihapus']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Akun tidak ditemukan',
                'id' => $id
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting sold account ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal menghapus akun',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
