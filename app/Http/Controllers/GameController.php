<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // GET /api/games
    public function index()
    {
        return Game::all();
    }

    // POST /api/games
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'image_url' => 'required|string',
            'banner_url' => 'nullable|string'
        ]);

        $game = Game::create($validated);

        return response()->json($game, 201);
    }

    // GET /api/games/{id}
    public function show($id)
    {
        $game = Game::with('products')->findOrFail($id);
        return response()->json($game);
    }

    // PUT /api/games/{id}
    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'image_url' => 'required|string',
            'banner_url' => 'nullable|string'
        ]);

        $game->update($validated);

        return response()->json($game);
    }

    // DELETE /api/games/{id}
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return response()->json(['message' => 'Game deleted successfully']);
    }
}
