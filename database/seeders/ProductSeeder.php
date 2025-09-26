<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Starters (category_id = 1)
            [
                'category_id' => 1,
                'name' => 'Paneer Tikka',
                'description' => 'Grilled cottage cheese with spices',
                'price' => 180.00,
                'is_veg' => true,
                'spice_level' => 'medium',
                'prep_time' => 15
            ],
            [
                'category_id' => 1,
                'name' => 'Chicken Tikka',
                'description' => 'Grilled chicken pieces with spices',
                'price' => 220.00,
                'is_veg' => false,
                'spice_level' => 'medium',
                'prep_time' => 20
            ],

            // Main Course (category_id = 2)
            [
                'category_id' => 2,
                'name' => 'Butter Chicken',
                'description' => 'Creamy tomato-based chicken curry',
                'price' => 280.00,
                'is_veg' => false,
                'spice_level' => 'mild',
                'prep_time' => 25
            ],
            [
                'category_id' => 2,
                'name' => 'Dal Tadka',
                'description' => 'Yellow lentils with tempering',
                'price' => 120.00,
                'is_veg' => true,
                'spice_level' => 'mild',
                'prep_time' => 15
            ],

            // Biryani (category_id = 3)
            [
                'category_id' => 3,
                'name' => 'Chicken Biryani',
                'description' => 'Fragrant basmati rice with spiced chicken',
                'price' => 320.00,
                'is_veg' => false,
                'spice_level' => 'medium',
                'prep_time' => 45
            ],
            [
                'category_id' => 3,
                'name' => 'Veg Biryani',
                'description' => 'Fragrant basmati rice with mixed vegetables',
                'price' => 250.00,
                'is_veg' => true,
                'spice_level' => 'medium',
                'prep_time' => 35
            ],

            // Beverages (category_id = 7)
            [
                'category_id' => 7,
                'name' => 'Masala Chai',
                'description' => 'Indian spiced tea',
                'price' => 30.00,
                'is_veg' => true,
                'spice_level' => 'mild',
                'prep_time' => 5
            ],
            [
                'category_id' => 7,
                'name' => 'Fresh Lime Soda',
                'description' => 'Refreshing lime soda',
                'price' => 60.00,
                'is_veg' => true,
                'spice_level' => 'mild',
                'prep_time' => 3
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
