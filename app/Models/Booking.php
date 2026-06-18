<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'space_id',
        'user_id',
        'client_name',
        'client_email',
        'client_phone',
        'booking_date',
        'start_time',
        'end_time',
        'selected_slots',
        'total_price',
        'service_fee',
        'payment_status',
        'staff_notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'selected_slots' => 'array',
        'total_price' => 'float',
        'service_fee' => 'float',
    ];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
