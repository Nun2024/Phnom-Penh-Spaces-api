<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceRequest;
use App\Http\Requests\UpdateSpaceRequest;
use App\Models\Space;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    public function index(Request $request)
    {
        $spaces = Space::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($request->space_type, function ($query, $spaceTypeInput) {
                $query->whereHas('spaceType', function($q) use ($spaceTypeInput) {
                    $q->where('name', $spaceTypeInput);
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($request->offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->get();

        return response()->json($spaces, 200);
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

    public function store(StoreSpaceRequest $request)
    {
        $fields = $request->validated();

        // Standard default image if not provided
        if (empty($fields['images'])) {
            $fields['images'] = ["https://lh3.googleusercontent.com/aida-public/AB6AXuApCqKB97pQMigDG4PXYMWpdEdBWZUpxDsEC7Yy5s09yBEYax2QnvJAtNsRiHwedKORP9qT5ox1IaPa_PmtammtYF1MQXwwVL_V8sgxDbUeAGX27moxj9SI2Ps4_b8-xjlXNjR-9KYzbueDPry5ziTLMUB9vBuBKftBm_AabmZbrVrRZJnj_T63FpOLMWBtRrmtnteU28Gi2E1e2IDmTXH8MORjX1atP7SRim3Gb_Sh1OBK4hyB1vwvqvxbBYC8WYjbqpLKXuGb9PU"];
        }

        $space = Space::create($fields);

        return response()->json($space, 201);
    }

    public function update(UpdateSpaceRequest $request, $id)
    {
        $space = Space::find($id);

        if (!$space) {
            return response()->json(['message' => 'Space not found'], 404);
        }

        $space->update($request->validated());

        return response()->json($space, 200);
    }
}
