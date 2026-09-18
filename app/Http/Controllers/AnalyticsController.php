<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Space;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function kpis()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();

        // 1. Total Revenue
        $currentRevenue = Booking::where('payment_status', 'paid')
            ->where('booking_date', '>=', $currentMonth)
            ->sum('total_price');
            
        $previousRevenue = Booking::where('payment_status', 'paid')
            ->whereBetween('booking_date', [$previousMonth, $currentMonth->copy()->subSecond()])
            ->sum('total_price');

        $revenueTrend = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        // 2. Total Booked Hours
        $currentBookings = Booking::where('booking_date', '>=', $currentMonth)->get();
        $previousBookings = Booking::whereBetween('booking_date', [$previousMonth, $currentMonth->copy()->subSecond()])->get();

        $getHours = function($bookings) {
            return $bookings->sum(function ($b) {
                return Carbon::parse($b->start_time)->diffInMinutes(Carbon::parse($b->end_time)) / 60;
            });
        };

        $currentHours = $getHours($currentBookings);
        $previousHours = $getHours($previousBookings);
        $hoursTrend = $previousHours > 0 ? (($currentHours - $previousHours) / $previousHours) * 100 : 0;

        // 3. Avg Occupancy Rate
        $spacesCount = Space::count();
        $daysInCurrentMonth = Carbon::now()->daysInMonth;
        // Total available hours = spaces * 24 hours * days in month
        $totalAvailableHours = $spacesCount * 24 * $daysInCurrentMonth;
        $currentOccupancy = $totalAvailableHours > 0 ? ($currentHours / $totalAvailableHours) * 100 : 0;
        
        $daysInPreviousMonth = Carbon::now()->subMonth()->daysInMonth;
        $prevAvailableHours = $spacesCount * 24 * $daysInPreviousMonth;
        $prevOccupancy = $prevAvailableHours > 0 ? ($previousHours / $prevAvailableHours) * 100 : 0;
        $occupancyTrend = $currentOccupancy - $prevOccupancy; // difference in percentage points

        // 4. RevPAH
        $currentRevPah = $currentHours > 0 ? $currentRevenue / $currentHours : 0;
        $previousRevPah = $previousHours > 0 ? $previousRevenue / $previousHours : 0;
        $revPahTrend = $previousRevPah > 0 ? (($currentRevPah - $previousRevPah) / $previousRevPah) * 100 : 0;

        return response()->json([
            'totalRevenue' => [
                'value' => round($currentRevenue, 2),
                'trend' => round(abs($revenueTrend), 1),
                'trendLabel' => "vs last month",
                'isPositive' => $revenueTrend >= 0,
                'isFlat' => $revenueTrend == 0
            ],
            'avgOccupancyRate' => [
                'value' => round($currentOccupancy, 1),
                'trend' => round(abs($occupancyTrend), 1),
                'trendLabel' => "vs last month",
                'isPositive' => $occupancyTrend >= 0,
                'isFlat' => $occupancyTrend == 0
            ],
            'totalBookedHours' => [
                'value' => round($currentHours),
                'trend' => round(abs($hoursTrend), 1),
                'trendLabel' => $hoursTrend >= 0 ? "increase" : "decrease",
                'isPositive' => $hoursTrend >= 0,
                'isFlat' => $hoursTrend == 0
            ],
            'revPah' => [
                'value' => round($currentRevPah, 2),
                'trend' => round(abs($revPahTrend), 1),
                'trendLabel' => "efficiency",
                'isPositive' => $revPahTrend >= 0,
                'isFlat' => $revPahTrend == 0
            ]
        ]);
    }

    public function trends()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentBookings = Booking::where('payment_status', 'paid')
            ->where('booking_date', '>=', $currentMonth)
            ->get();
            
        $previousBookings = Booking::where('payment_status', 'paid')
            ->whereBetween('booking_date', [$previousMonth, $currentMonth->copy()->subSecond()])
            ->get();

        $weeklyData = [];
        $highestDayRevenue = 0;
        $highestDayName = "";

        for ($i = 1; $i <= 4; $i++) {
            $weekStart = $currentMonth->copy()->addWeeks($i - 1);
            $weekEnd = $i == 4 ? Carbon::now()->endOfMonth() : $currentMonth->copy()->addWeeks($i)->subSecond();
            
            $prevWeekStart = $previousMonth->copy()->addWeeks($i - 1);
            $prevWeekEnd = $i == 4 ? $currentMonth->copy()->subSecond() : $previousMonth->copy()->addWeeks($i)->subSecond();

            $currRev = $currentBookings->filter(function($b) use ($weekStart, $weekEnd) {
                return Carbon::parse($b->booking_date)->between($weekStart, $weekEnd);
            })->sum('total_price');

            $prevRev = $previousBookings->filter(function($b) use ($prevWeekStart, $prevWeekEnd) {
                return Carbon::parse($b->booking_date)->between($prevWeekStart, $prevWeekEnd);
            })->sum('total_price');

            $weeklyData[] = [
                'week' => "Wk $i",
                'currentRevenue' => $currRev,
                'previousRevenue' => $prevRev,
                'isPeak' => false
            ];
        }

        // Find peak week
        if (count($weeklyData) > 0) {
            $maxRev = collect($weeklyData)->max('currentRevenue');
            foreach ($weeklyData as &$week) {
                if ($week['currentRevenue'] == $maxRev && $maxRev > 0) {
                    $week['isPeak'] = true;
                }
            }
        }

        // Highest grossing day
        $dailyRevenues = $currentBookings->groupBy(function($b) {
            return Carbon::parse($b->booking_date)->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('total_price');
        });

        if ($dailyRevenues->count() > 0) {
            $highestDayDate = $dailyRevenues->sortDesc()->keys()->first();
            $highestDayRevenue = $dailyRevenues[$highestDayDate];
            $date = Carbon::parse($highestDayDate);
            $weekNum = ceil($date->day / 7);
            $highestDayName = $date->format('l') . ", Wk $weekNum";
        }

        return response()->json([
            'period' => "This Period",
            'periodTotal' => $currentBookings->sum('total_price'),
            'previousPeriodTotal' => $previousBookings->sum('total_price'),
            'weeklyData' => $weeklyData,
            'highestGrossingDay' => [
                'day' => $highestDayName ?: "N/A",
                'revenue' => $highestDayRevenue
            ]
        ]);
    }

    public function utilization()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $daysInMonth = Carbon::now()->daysInMonth;
        $totalAvailableHoursPerSpace = 24 * $daysInMonth;

        $spaces = Space::with(['bookings' => function($q) use ($currentMonth) {
            $q->where('booking_date', '>=', $currentMonth);
        }])->get();

        $colors = ['bg-primary', 'bg-primary-container', 'bg-secondary', 'bg-outline'];
        
        $data = $spaces->map(function($space, $index) use ($totalAvailableHoursPerSpace, $colors) {
            $revenue = $space->bookings->where('payment_status', 'paid')->sum('total_price');
            $hours = $space->bookings->sum(function($b) {
                return Carbon::parse($b->start_time)->diffInMinutes(Carbon::parse($b->end_time)) / 60;
            });
            $utilization = $totalAvailableHoursPerSpace > 0 ? ($hours / $totalAvailableHoursPerSpace) * 100 : 0;

            return [
                'id' => $space->id,
                'name' => $space->name,
                'revenue' => round($revenue, 2),
                'utilizationPercentage' => round($utilization, 1),
                'color' => $colors[$index % count($colors)]
            ];
        });

        return response()->json($data);
    }

    public function heatmap()
    {
        $bookings = Booking::where('booking_date', '>=', Carbon::now()->subMonths(3))->get();
        
        $dayCounts = array_fill(0, 7, 0); // Mon=0..Sun=6
        $periodCounts = [
            'Morning' => array_fill(0, 7, 0),
            'Mid-day Peak' => array_fill(0, 7, 0),
            'Evening' => array_fill(0, 7, 0)
        ];

        foreach ($bookings as $b) {
            $date = Carbon::parse($b->booking_date);
            $dayIndex = $date->dayOfWeekIso - 1; 
            $dayCounts[$dayIndex]++;

            $hour = Carbon::parse($b->start_time)->hour;
            if ($hour >= 6 && $hour < 11) {
                $periodCounts['Morning'][$dayIndex]++;
            } elseif ($hour >= 11 && $hour < 16) {
                $periodCounts['Mid-day Peak'][$dayIndex]++;
            } else {
                $periodCounts['Evening'][$dayIndex]++;
            }
        }

        $days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        $maxDayIndex = 0;
        if (count($dayCounts) > 0 && max($dayCounts) > 0) {
            $maxDayIndex = array_keys($dayCounts, max($dayCounts))[0];
        }
        $busiestDay = $days[$maxDayIndex] ?? "N/A";

        return response()->json([
            'busiestDays' => $busiestDay,
            'primeHours' => "11:00 AM - 04:00 PM", 
            'matrix' => [
                'days' => $days,
                'periods' => [
                    [
                        'name' => "Morning",
                        'data' => $periodCounts['Morning']
                    ],
                    [
                        'name' => "Mid-day Peak",
                        'data' => $periodCounts['Mid-day Peak']
                    ],
                    [
                        'name' => "Evening",
                        'data' => $periodCounts['Evening']
                    ]
                ]
            ]
        ]);
    }

    public function spacesPerformance()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();

        $spaces = Space::with(['bookings' => function($q) use ($previousMonth) {
            $q->where('booking_date', '>=', $previousMonth);
        }])->get();

        $data = $spaces->map(function($space) use ($currentMonth) {
            $currentBookings = $space->bookings->filter(function($b) use ($currentMonth) {
                return Carbon::parse($b->booking_date)->gte($currentMonth);
            });
            $previousBookings = $space->bookings->filter(function($b) use ($currentMonth) {
                return Carbon::parse($b->booking_date)->lt($currentMonth);
            });

            $totalBookings = $currentBookings->count();
            $cancelled = $currentBookings->where('payment_status', 'cancelled')->count();
            $cancellationRate = $totalBookings > 0 ? ($cancelled / $totalBookings) * 100 : 0;

            $totalHours = $currentBookings->sum(function($b) {
                return Carbon::parse($b->start_time)->diffInMinutes(Carbon::parse($b->end_time)) / 60;
            });
            $avgDuration = $totalBookings > 0 ? $totalHours / $totalBookings : 0;

            $currentRev = $currentBookings->where('payment_status', 'paid')->sum('total_price');
            $prevRev = $previousBookings->where('payment_status', 'paid')->sum('total_price');
            $trend = $prevRev > 0 ? (($currentRev - $prevRev) / $prevRev) * 100 : 0;

            return [
                'id' => $space->id,
                'name' => $space->name,
                'location' => $space->location,
                'image' => is_array($space->images) && count($space->images) > 0 ? $space->images[0] : "https://placehold.co/100x100?text=Space",
                'totalBookings' => [
                    'value' => $totalBookings
                ],
                'totalHours' => [
                    'value' => round($totalHours, 1)
                ],
                'avgDuration' => [
                    'value' => round($avgDuration, 1)
                ],
                'cancellationRate' => [
                    'value' => round($cancellationRate, 1)
                ],
                'totalRevenue' => [
                    'value' => round($currentRev, 2)
                ],
                'trend' => [
                    'value' => round(abs($trend), 1),
                    'isPositive' => $trend >= 0,
                    'isFlat' => $trend == 0
                ]
            ];
        });

        return response()->json($data);
    }
}
