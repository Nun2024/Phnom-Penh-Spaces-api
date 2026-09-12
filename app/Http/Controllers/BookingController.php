<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
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

    public function store(StoreBookingRequest $request)
    {
        $fields = $request->validated();

        // Automatically assign user_id if authenticated
        $userId = $request->user() ? $request->user()->id : null;

        $booking = Booking::create(array_merge($fields, [
            'user_id' => $userId,
            'service_fee' => $fields['service_fee'] ?? 5.00,
            'payment_status' => $fields['payment_status'] ?? 'unpaid',
        ]));

        return response()->json($booking->load('space'), 201);
    }

    public function update(UpdateBookingRequest $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->update($request->validated());

        return response()->json($booking->load('space'), 200);
    }
}
