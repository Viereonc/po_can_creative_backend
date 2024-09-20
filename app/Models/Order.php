<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'ticket_id',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'integer',
        'status' => 'string',
    ];

    // Relationships ------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

    // Accessors ----------------------------------------------------------------
    public function statusAttribute($value)
    {
        return ucfirst($value);
    }

    // Mutators -----------------------------------------------------------------
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }

    // Scopes -------------------------------------------------------------------
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancel');
    }

    // Custom Methods -----------------------------------------------------------
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isCancelled()
    {
        return $this->status === 'cancel';
    }
}
