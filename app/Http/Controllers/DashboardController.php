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

        // 3. Occupancy Rate (Placeholder matching UI for now)
        $occupancyRate = 78; 

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
}
