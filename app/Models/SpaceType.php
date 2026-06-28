<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpaceType extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'description',
    ];

    public function spaces()
    {
        return $this->hasMany(Space::class);
    }
}
