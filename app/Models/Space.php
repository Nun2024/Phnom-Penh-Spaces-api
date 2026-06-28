<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Space extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'location',
        'space_type_id',
        'price_per_hour',
        'capacity',
        'description',
        'status',
        'images',
        'wifi',
        'whiteboard',
        'ac',
        'soundproofing',
        'natural_light',
        'refreshments',
    ];

    protected $casts = [
        'price_per_hour' => 'float',
        'capacity' => 'integer',
        'images' => 'array',
        'wifi' => 'boolean',
        'whiteboard' => 'boolean',
        'ac' => 'boolean',
        'soundproofing' => 'boolean',
        'natural_light' => 'boolean',
        'refreshments' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function spaceType()
    {
        return $this->belongsTo(SpaceType::class);
    }
}
