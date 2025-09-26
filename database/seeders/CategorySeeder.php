<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Starters', 'sort_order' => 1],
            ['name' => 'Main Course', 'sort_order' => 2],
            ['name' => 'Biryani', 'sort_order' => 3],
            ['name' => 'Chinese', 'sort_order' => 4],
            ['name' => 'South Indian', 'sort_order' => 5],
            ['name' => 'Desserts', 'sort_order' => 6],
            ['name' => 'Beverages', 'sort_order' => 7],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
