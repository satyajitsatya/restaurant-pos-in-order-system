<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that can be mass assigned.
     * These fields can be filled using create() or fill() methods
     */
    protected $fillable = [
        'name',
        'image',
        'sort_order',
        'is_active'
    ];

    /**
     * Define relationship: A category has many products
     * This allows us to get all products in a category using $category->products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // New relationship to ONLY active products
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', 1);
        // OR, if 'status' is a column with a string value like 'active':
        // return $this->hasMany(Product::class)->where('status', 'active');
    }

    /**
     * Scope to get only active categories
     * Usage: Category::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
