<?php

namespace App\Http\Controllers;

use App\Models\SoldAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SoldAccountController extends Controller
{
    // GET semua akun (admin only - dashboard)
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

    // GET akun untuk public (hanya aktif) & admin (semua)
    public function index()
    {
        try {
            if (auth('api')->check() && auth('api')->user()->role === 'admin') { // UBAH INI
                $accounts = SoldAccount::orderBy('order')->get();
            } else {
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

    // POST - Buat akun baru + upload gambar
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'required|integer|min:0',
                'image'       => 'required|string',
                'gallery'     => 'nullable|array',
                'gallery.*'   => 'nullable|string',
                'order'       => 'nullable|integer',
                'is_active'   => 'nullable|boolean',
            ]);

            // Upload gambar utama
            $imageUrl = $request->image_url; // langsung pakai dari request
            $galleryUrls = $request->gallery ?? []; // langsung pakai array dari request

            $account = SoldAccount::create([
                'title'       => $request->title,
                'description' => $request->description,
                'price'       => $request->price,
                'image_url'   => $imageUrl,
                'gallery'     => $galleryUrls,
                'order'       => $request->order ?? 0,
                'is_active'   => $request->boolean('is_active', true),
            ]);

            Log::info('Sold account created: ' . $account->id);
            return response()->json($account, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating sold account: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan akun', 'message' => $e->getMessage()], 500);
        }
    }

    // GET detail akun
    public function show($id)
    {
        try {
            $account = SoldAccount::findOrFail($id);
            return response()->json($account);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Akun tidak ditemukan', 'id' => $id], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching sold account ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat akun', 'message' => $e->getMessage()], 500);
        }
    }

    // PUT - Update akun + ganti gambar jika ada
    public function update(Request $request, $id)
    {
        try {
            $account = SoldAccount::findOrFail($id);

            $request->validate([
                'title'       => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'sometimes|required|integer|min:0',
                'image'       => 'sometimes|string',
                'gallery'     => 'nullable|array',
                'gallery.*'   => 'nullable|string',
                'order'       => 'nullable|integer',
                'is_active'   => 'nullable|boolean',
            ]);

            // Update gambar utama jika ada file baru
            // Update gambar utama jika ada
            if ($request->filled('image_url')) {
                $account->image_url = $request->image_url;
            }

            // Update gallery jika ada file baru (timpa semua)
            if ($request->has('gallery')) {
                $account->gallery = $request->gallery ?? [];
            }

            // Update field teks lain
            $account->title       = $request->filled('title') ? $request->title : $account->title;
            $account->description = $request->has('description') ? $request->description : $account->description;
            $account->price       = $request->filled('price') ? $request->price : $account->price;
            $account->order       = $request->has('order') ? $request->order : $account->order;
            $account->is_active   = $request->has('is_active') ? $request->boolean('is_active') : $account->is_active;

            $account->save();

            Log::info('Sold account updated: ' . $account->id);
            return response()->json($account);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error updating sold account ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengupdate akun', 'message' => $e->getMessage()], 500);
        }
    }

    // DELETE - Hapus akun + file gambar
    public function destroy($id)
    {
        try {
            $account = SoldAccount::findOrFail($id);

            // Hapus gambar utama
            if ($account->image_url) {
                $oldMain = str_replace(asset('storage/'), '', $account->image_url);
                Storage::disk('public')->delete($oldMain);
            }

            // Hapus gallery
            if ($account->gallery) {
                foreach ($account->gallery as $oldUrl) {
                    $oldPath = str_replace(asset('storage/'), '', $oldUrl);
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $account->delete();

            Log::info('Sold account deleted: ' . $id);
            return response()->json(['message' => 'Akun berhasil dihapus']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Akun tidak ditemukan', 'id' => $id], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting sold account ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus akun', 'message' => $e->getMessage()], 500);
        }
    }
}
