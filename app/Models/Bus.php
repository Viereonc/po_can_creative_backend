<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $primaryKey = 'bus_id';

    protected $table = 'buses';

    protected $fillable = [
        'bus_name',
        'seat_capacity',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'seat_capacity' => 'integer',
    ];

    //Relationships ------------------------------------------------------------
    public function destination()
    {
        return $this->hasMany(Destination::class, 'destination_id', 'destination_id');
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class, 'bus_id', 'bus_id');
    }

    //Accesso ------------------------------------------------------------------
    public function busNameAttribute($value)
    {
        return ucwords($value);
    }

    //Mutator ------------------------------------------------------------------
    public function setNameAttribute($value)
    {
        $this->attributes['bus_name'] = strtolower($value);
    }

    //Scopes -------------------------------------------------------------------
    public function scopeCapacityAbove($query, $capacity)
    {
        return $query->where('seat_capacity', '>=', $capacity);
    }

    public function scopeAvailableOnDestination($query, $destinationId)
    {
        return $query->whereHas('routes', function ($q) use ($destinationId) {
            $q->where('destination_id', $destinationId);
        });
    }

    //Custom Methods -----------------------------------------------------------
    public function hasAvailableSeats($bookedSeats)
    {
        return $this->seat_capacity - $bookedSeats > 0;
    }
}
