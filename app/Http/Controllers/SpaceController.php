<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Space::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->has('space_type')) {
            $spaceTypeInput = $request->input('space_type');
            $query->whereHas('spaceType', function($q) use ($spaceTypeInput) {
                $q->where('name', $spaceTypeInput);
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('limit')) {
            $query->limit($request->input('limit'));
        }

        if ($request->has('offset')) {
            $query->offset($request->input('offset'));
        }

        return response()->json($query->get(), 200);
    }

    public function show($id)
    {
        $space = Space::find($id);

        if (!$space) {
            return response()->json(['message' => 'Space not found'], 404);
        }

        return response()->json($space, 200);
    }

    public function bookings($id)
    {
        $bookings = \App\Models\Booking::where('space_id', $id)->get(['selected_slots']);
        return response()->json($bookings, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'space_type' => 'required|string|in:podcast,meeting,gallery,workshop',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'status' => 'nullable|string|in:Active,Maintenance',
            'images' => 'nullable|array',
            'wifi' => 'nullable|boolean',
            'whiteboard' => 'nullable|boolean',
            'ac' => 'nullable|boolean',
            'soundproofing' => 'nullable|boolean',
            'natural_light' => 'nullable|boolean',
            'refreshments' => 'nullable|boolean',
        ]);

        // Standard default image if not provided
        if (empty($fields['images'])) {
            $fields['images'] = ["https://lh3.googleusercontent.com/aida-public/AB6AXuApCqKB97pQMigDG4PXYMWpdEdBWZUpxDsEC7Yy5s09yBEYax2QnvJAtNsRiHwedKORP9qT5ox1IaPa_PmtammtYF1MQXwwVL_V8sgxDbUeAGX27moxj9SI2Ps4_b8-xjlXNjR-9KYzbueDPry5ziTLMUB9vBuBKftBm_AabmZbrVrRZJnj_T63FpOLMWBtRrmtnteU28Gi2E1e2IDmTXH8MORjX1atP7SRim3Gb_Sh1OBK4hyB1vwvqvxbBYC8WYjbqpLKXuGb9PU"];
        }

        $space = Space::create($fields);

        return response()->json($space, 201);
    }

    public function update(Request $request, $id)
    {
        $space = Space::find($id);

        if (!$space) {
            return response()->json(['message' => 'Space not found'], 404);
        }

        $fields = $request->validate([
            'name' => 'sometimes|required|string',
            'location' => 'sometimes|required|string',
            'space_type' => 'sometimes|required|string|in:podcast,meeting,gallery,workshop',
            'price_per_hour' => 'sometimes|required|numeric|min:0',
            'capacity' => 'sometimes|required|integer|min:1',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|string|in:Active,Maintenance',
            'images' => 'nullable|array',
            'wifi' => 'nullable|boolean',
            'whiteboard' => 'nullable|boolean',
            'ac' => 'nullable|boolean',
            'soundproofing' => 'nullable|boolean',
            'natural_light' => 'nullable|boolean',
            'refreshments' => 'nullable|boolean',
        ]);

        $space->update($fields);

        return response()->json($space, 200);
    }
}
