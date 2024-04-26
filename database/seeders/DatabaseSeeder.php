<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enum\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'id' => 147369258,
            'name' => 'Admin User',
            'role' => UserRoleEnum::ADMIN,
            'phone_number' => '0123456789',
            'address' => 'Admin-address',
            'email' => 'Admin@gmail.com',
            'password' => Hash::make('Admin'),
        ]);
        $this->call(CouponSeeder::class);
    }
}
