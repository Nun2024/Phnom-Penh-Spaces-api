<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Space;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        // 1. Total Bookings
        $totalBookings = Booking::count();

        // 2. Gross Revenue (from paid bookings only)
        // For simplicity, we might just sum all, but let's sum 'paid' or all if none are marked 'paid' yet
        $grossRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
        
        if ($grossRevenue == 0) {
            $grossRevenue = Booking::sum('total_price'); // Fallback if payment_status isn't strictly used
        }

        // 3. Occupancy Rate (Spaces booked today / Total spaces)
        $totalSpaces = Space::count();
        $spacesBookedToday = Booking::whereDate('booking_date', today())->distinct('space_id')->count('space_id');
        $occupancyRate = $totalSpaces > 0 ? round(($spacesBookedToday / $totalSpaces) * 100) : 0;

        // 4. Upcoming Reservations (recent 10 bookings)
        $upcomingReservations = Booking::with('space', 'user')
                                       ->orderBy('created_at', 'desc')
                                       ->take(10)
                                       ->get();

        // 5. Weekly Revenue (Current week from Monday to Sunday)
        $weeklyRevenue = [];
        $startOfWeek = now()->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dayName = $date->format('D'); // Mon, Tue, etc.
            // Calculate revenue for that specific day
            $revenue = Booking::whereDate('created_at', $date->format('Y-m-d'))->sum('total_price');
            
            $weeklyRevenue[] = [
                'day' => $dayName,
                'revenue' => (float)$revenue
            ];
        }

        return response()->json([
            'totalBookings' => $totalBookings,
            'grossRevenue' => $grossRevenue,
            'occupancyRate' => $occupancyRate,
            'upcomingReservations' => $upcomingReservations,
            'weeklyRevenue' => $weeklyRevenue
        ], 200);
    }

    public function reservations(Request $request)
    {
        // Fetch all bookings with related space and user information
        $reservations = Booking::with('space', 'user')
                               ->orderBy('booking_date', 'desc')
                               ->where('payment_status', 'unpaid')
                               ->get();

        return response()->json([
            'reservations' => $reservations
        ], 200);
    }

    public function revenue(Request $request)
    {
        // Calculate total revenue from paid bookings
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');

        // Calculate total service fees collected
        $totalServiceFees = Booking::where('payment_status', 'paid')->sum('service_fee');

        return response()->json([
            'totalRevenue' => $totalRevenue,
            'totalServiceFees' => $totalServiceFees
        ], 200);
    }

    public function weeklyReport(Request $request)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $bookings = Booking::with('space', 'user')
                           ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                           ->orderBy('created_at', 'desc')
                           ->get();

        $totalRevenue = $bookings->where('payment_status', 'paid')->sum('total_price');
        
        $dailyBreakdown = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dayName = $date->format('D');
            
            $dayBookings = $bookings->filter(function ($booking) use ($date) {
                return $booking->created_at->format('Y-m-d') === $date->format('Y-m-d');
            });
            
            $dayRevenue = $dayBookings->where('payment_status', 'paid')->sum('total_price');

            $dailyBreakdown[] = [
                'day' => $dayName,
                'date' => $date->format('Y-m-d'),
                'revenue' => (float)$dayRevenue,
                'bookings_count' => $dayBookings->count(),
            ];
        }

        return response()->json([
            'week_start' => $startOfWeek->format('Y-m-d'),
            'week_end' => $endOfWeek->format('Y-m-d'),
            'total_revenue' => $totalRevenue,
            'total_bookings' => $bookings->count(),
            'daily_breakdown' => $dailyBreakdown,
            'bookings' => $bookings
        ], 200);
    }
}
