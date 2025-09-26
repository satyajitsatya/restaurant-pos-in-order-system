<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'customer_name',
        'guest_count',
        'total_amount',
        'status',
        'payment_method',
        'notes'
    ];

    /**
     * An order belongs to a table
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * An order has many items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Calculate total amount from order items
     */
    public function calculateTotal()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format($this->total_amount, 0);
    }
}
