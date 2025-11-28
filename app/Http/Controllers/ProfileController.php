<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        // Log untuk debugging
        Log::info('Request data:', $request->all());
        Log::info('Files:', $request->allFiles());

        // VALIDASI
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'avatar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'password' => 'sometimes|nullable|string|min:6',
        ]);

        // UPDATE NAME
        if ($request->filled('name')) {
            $user->name = $validated['name'];
        }

        // UPDATE EMAIL
        if ($request->filled('email')) {
            $user->email = $validated['email'];
        }

        // UPDATE PASSWORD
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        // UPDATE AVATAR
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                $oldPath = str_replace(asset('storage/'), '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }

            // Upload avatar baru
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('avatars', $filename, 'public');

            $user->avatar = asset('storage/' . $path);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
