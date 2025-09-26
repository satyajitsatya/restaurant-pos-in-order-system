<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@restaurant.com',
                'mobile' => '1234567890',
                'password' => Hash::make('password123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Kitchen Staff',
                'email' => 'kitchen@restaurant.com',
                'mobile' => '1234567891',
                'password' => Hash::make('password123'),
                'role' => 'kitchen'
            ],
            [
                'name' => 'Waiter',
                'email' => 'waiter@restaurant.com',
                'mobile' => '1234567892',
                'password' => Hash::make('password123'),
                'role' => 'staff'
            ],
            [
                'name' => 'customer',
                'email' => 'customer@name.com',
                'mobile' => '1234567893',
                'password' => Hash::make('password123'),
                'role' => 'customer'
            ],


        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
