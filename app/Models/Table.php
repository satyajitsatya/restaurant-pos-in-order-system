<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'qr_code',
        'is_active'
    ];

    /**
     * A table can have many orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get current active order for this table
     */
    public function currentOrder()
    {
        return $this->orders()
            ->whereIn('status', ['pending', 'accepted', 'preparing', 'ready'])
            ->latest()
            ->first();
    }
}
