<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'phone_number' => '0999999999',
            'address' => 'New York',
            'dob' => '9/11/2001',
            'gender' => 'male'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Steve',
            'email' => 'steve@gmail.com',
            'role' => 'staff',
            'phone_number' => '0999999999',
            'address' => 'New York',
            'dob' => '9/11/2001',
            'gender' => 'male'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Stu',
            'email' => 'stu@gmail.com',
            'role' => 'staff',
            'phone_number' => '0999999999',
            'address' => 'New York',
            'dob' => '9/11/2001',
            'gender' => 'male'
        ]);

        $this->call([
            BrandSeeder::class,
            ProductSeeder::class,
            StockSeeder::class,
            VoucherSeeder::class,
            DailySaleOverviewSeeder::class
        ]);
    }
}
