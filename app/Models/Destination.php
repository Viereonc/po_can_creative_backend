<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $primaryKey = 'destination_id';

    protected $table = 'destinations';

    protected $fillable = [
        'bus_id',
        'start_location',
        'end_location',
        'departure_time',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
    ];

    //Relationships ------------------------------------------------------------
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id', 'bus_id');
    }

    //Accessor -----------------------------------------------------------------
    public function destinationNameAttribute($value)
    {
        return ucwords($value);
    }

    //Scopes -------------------------------------------------------------------
    public function scopeByLocation($query, $locationStart, $locationEnd)
    {
        return $query->where('start_location', 'like', "%$locationStart%")
            ->where('end_location', 'like', "%$locationEnd%");
    }

    //Custom Methods -----------------------------------------------------------
    public function getDestination()
    {
        return $this->start_location . ' - ' . $this->end_location;
    }

    public function getDepartureTime()
    {
        return $this->departure_time->format('Y-m-d H:i:s');
    }

    public function hasDeparted()
    {
        return now()->greaterThan($this->departure_time);
    }

    public function hasNotDeparted()
    {
        return now()->lessThan($this->departure_time);
    }
}
