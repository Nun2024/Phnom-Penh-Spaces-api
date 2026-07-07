<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Space;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user && $user->role === 'ADMIN') {
            $bookings = Booking::with('space')->get();
        } elseif ($user) {
            $bookings = Booking::where('user_id', $user->id)
                               ->orWhere('client_email', $user->email)
                               ->with('space')->get();
        } else {
            // Guest or public requests (should be protected by middleware normally, but support list fallback)
            $bookings = Booking::with('space')->get();
        }

        return response()->json($bookings, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'client_phone' => 'required|string',
            'booking_date' => 'required|date',
            'start_time' => 'required|string', // format "HH:MM"
            'end_time' => 'required|string',   // format "HH:MM"
            'selected_slots' => 'required|array', // list of slots, e.g. ["THU-08:00", "THU-09:00"]
            'total_price' => 'required|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|string|in:unpaid,partial,paid',
            'staff_notes' => 'nullable|string',
        ]);

        // Automatically assign user_id if authenticated
        $userId = $request->user() ? $request->user()->id : null;

        $booking = Booking::create(array_merge($fields, [
            'user_id' => $userId,
            'service_fee' => $fields['service_fee'] ?? 5.00,
            'payment_status' => $fields['payment_status'] ?? 'unpaid',
        ]));

        return response()->json($booking->load('space'), 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $fields = $request->validate([
            'payment_status' => 'sometimes|required|string|in:unpaid,partial,paid',
            'staff_notes' => 'nullable|string',
            'client_name' => 'sometimes|required|string',
            'client_email' => 'sometimes|required|email',
            'client_phone' => 'sometimes|required|string',
        ]);

        $booking->update($fields);

        return response()->json($booking->load('space'), 200);
    }
}
