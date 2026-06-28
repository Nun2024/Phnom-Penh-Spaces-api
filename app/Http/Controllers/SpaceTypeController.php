<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpaceType;

class SpaceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(SpaceType::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:space_types',
            'description' => 'nullable|string',
        ]);

        $spaceType = SpaceType::create($validated);

        return response()->json($spaceType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $spaceType = SpaceType::findOrFail($id);
        return response()->json($spaceType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $spaceType = SpaceType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:space_types,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $spaceType->update($validated);

        return response()->json($spaceType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $spaceType = SpaceType::findOrFail($id);
        $spaceType->delete();

        return response()->json(null, 204);
    }
}
