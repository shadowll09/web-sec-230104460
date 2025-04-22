<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        // Create employee user
        $employee = User::create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
        ]);
        $employee->assignRole('Employee');

        // Create customer users
        $customer1 = User::create([
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
            'credits' => 5000,
        ]);
        $customer1->assignRole('Customer');

        $customer2 = User::create([
            'name' => 'Customer Two',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
            'credits' => 8000,
        ]);
        $customer2->assignRole('Customer');

        // Seed products
        $this->call(ProductSeeder::class);
    }
}
