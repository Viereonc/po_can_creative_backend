<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_id';

    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'destination_id',
        'seat_number',
        'price',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    //Relationships ------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'destination_id');
    }

    //Accessor -----------------------------------------------------------------
    public function formattedPriceAttribute()
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    //Scopes -------------------------------------------------------------------
    public function scopePriceAbove($query, $amount)
    {
        return $query->where('price', '>=', $amount);
    }

    public function scopeForDestination($query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }

    //Custom Methods -----------------------------------------------------------
    public function isSold()
    {
        return $this->user_id !== null;
    }

    public function getTicketInfo()
    {
        return "Tiket untuk kursi {$this->seat_number} menuju destinasi {$this->destination->name} dengan harga {$this->price}";
    }
}
