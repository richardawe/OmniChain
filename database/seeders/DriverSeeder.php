<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FreightOrder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test driver
        User::create([
            'name' => 'John Driver',
            'email' => 'driver@omnichain.com',
            'password' => Hash::make('password'),
            'phone' => '+1-555-0123',
            'driver_license' => 'DL123456789',
            'vehicle_type' => 'van',
            'max_capacity' => 1000.00,
            'status' => 'available',
            'email_verified_at' => now(),
        ]);

        // Create additional test drivers
        User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah@omnichain.com',
            'password' => Hash::make('password'),
            'phone' => '+1-555-0124',
            'driver_license' => 'DL987654321',
            'vehicle_type' => 'truck',
            'max_capacity' => 2000.00,
            'status' => 'available',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@omnichain.com',
            'password' => Hash::make('password'),
            'phone' => '+1-555-0125',
            'driver_license' => 'DL456789123',
            'vehicle_type' => 'car',
            'max_capacity' => 500.00,
            'status' => 'busy',
            'email_verified_at' => now(),
        ]);

        // Assign existing freight orders to the first driver
        $driver = User::where('email', 'driver@omnichain.com')->first();
        if ($driver) {
            FreightOrder::whereNull('assigned_driver_id')
                ->whereIn('status', ['booked', 'quoted'])
                ->take(3)
                ->update(['assigned_driver_id' => $driver->id]);
        }
    }
}
