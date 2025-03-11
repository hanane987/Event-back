<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;  
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'name' => 'Admin User',  
            'email' => 'admin@cysc.fr',  
            'password' => Hash::make('password'),  
            'role' => 'admin',  
            'profile_image' => null,  
            'phone' => '1234567890',  
        ]);

        // Create an attendee user (for testing)
        User::create([
            'name' => 'Attendee User',
            'email' => 'attendee@cysc.fr',
            'password' => Hash::make('password'),
            'role' => 'attendee',
            'profile_image' => null,
            'phone' => '0987654321',
        ]);
    }
}
