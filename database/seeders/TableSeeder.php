<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 tables
        for ($i = 1; $i <= 20; $i++) {
            Table::create([
                'table_number' => 'T' . str_pad($i, 2, '0', STR_PAD_LEFT), // T01, T02, etc.
                'qr_code' => 'qr_table_' . $i,
                'is_active' => true
            ]);
        }
    }
}
