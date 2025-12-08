<?php

namespace App\Http\Controllers;

use App\Models\BackgroundMusic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BackgroundMusicController extends Controller
{
    // GET semua musik (untuk admin)
    public function index()
    {
        $musics = BackgroundMusic::ordered()->get();
        return response()->json($musics);
    }

    // GET musik aktif (untuk public/user)
    public function getActive()
    {
        $music = BackgroundMusic::active()->ordered()->first();
        return response()->json($music);
    }

    // POST - Upload musik baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'audio' => 'required|file|mimes:mp3,wav,ogg|max:10240', // max 10MB
            'is_active' => 'boolean',
        ]);

        try {
            // Upload file audio
            $audioFile = $request->file('audio');
            $filename = time() . '_' . uniqid() . '.' . $audioFile->getClientOriginalExtension();
            $path = $audioFile->storeAs('music', $filename, 'public');

            // Jika musik ini diset aktif, nonaktifkan musik lain
            if ($request->boolean('is_active')) {
                BackgroundMusic::where('is_active', true)->update(['is_active' => false]);
            }

            // Buat record baru
            $music = BackgroundMusic::create([
                'title' => $validated['title'],
                'artist' => $validated['artist'] ?? null,
                'audio_url' => asset('storage/' . $path),
                'is_active' => $request->boolean('is_active'),
                'order' => BackgroundMusic::max('order') + 1,
            ]);

            return response()->json([
                'message' => 'Background music uploaded successfully',
                'data' => $music
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error uploading music: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to upload music',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT - Update musik
    public function update(Request $request, $id)
    {
        $music = BackgroundMusic::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'artist' => 'nullable|string|max:255',
            'audio' => 'sometimes|file|mimes:mp3,wav,ogg|max:10240',
            'is_active' => 'boolean',
        ]);

        try {
            // Jika ada file audio baru
            if ($request->hasFile('audio')) {
                // Hapus file lama
                $oldPath = str_replace(asset('storage/'), '', $music->audio_url);
                Storage::disk('public')->delete($oldPath);

                // Upload file baru
                $audioFile = $request->file('audio');
                $filename = time() . '_' . uniqid() . '.' . $audioFile->getClientOriginalExtension();
                $path = $audioFile->storeAs('music', $filename, 'public');
                $music->audio_url = asset('storage/' . $path);
            }

            // Update fields lain
            if ($request->filled('title')) {
                $music->title = $validated['title'];
            }

            if ($request->has('artist')) {
                $music->artist = $validated['artist'];
            }

            // Jika musik ini diset aktif, nonaktifkan musik lain
            if ($request->boolean('is_active')) {
                BackgroundMusic::where('id', '!=', $id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
                $music->is_active = true;
            } elseif ($request->has('is_active')) {
                $music->is_active = false;
            }

            $music->save();

            return response()->json([
                'message' => 'Background music updated successfully',
                'data' => $music
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating music: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update music',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE - Hapus musik
    public function destroy($id)
    {
        $music = BackgroundMusic::findOrFail($id);

        try {
            // Hapus file audio
            $path = str_replace(asset('storage/'), '', $music->audio_url);
            Storage::disk('public')->delete($path);

            $music->delete();

            return response()->json([
                'message' => 'Background music deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting music: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete music',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST - Set musik aktif
    public function setActive($id)
    {
        try {
            // Nonaktifkan semua musik
            BackgroundMusic::where('is_active', true)->update(['is_active' => false]);

            // Aktifkan musik yang dipilih
            $music = BackgroundMusic::findOrFail($id);
            $music->is_active = true;
            $music->save();

            return response()->json([
                'message' => 'Background music activated successfully',
                'data' => $music
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to activate music',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
