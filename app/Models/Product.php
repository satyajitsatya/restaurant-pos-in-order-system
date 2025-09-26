<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_veg',
        'spice_level',
        'prep_time',
        'is_active'
    ];

    /**
     * Define relationship: A product belongs to a category
     * This allows us to get category info using $product->category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define relationship: A product can be in many orders
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope to get only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only vegetarian products
     */
    public function scopeVegetarian($query)
    {
        return $query->where('is_veg', true);
    }

    /**
     * Format price for display (adds ₹ symbol)
     */
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 0);
    }
}
