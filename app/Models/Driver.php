<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $primaryKey = 'driver_id';

    protected $fillable = [
        'user_id',
        'bus_id',
        'license_number',
    ];

    // Relationships ------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id', 'bus_id');
    }

    // Accessors ----------------------------------------------------------------
    public function licenseNumberAttribute($value)
    {
        return strtoupper($value);
    }

    // Scopes -------------------------------------------------------------------
    public function scopeByBus($query, $busId)
    {
        return $query->where('bus_id', $busId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
