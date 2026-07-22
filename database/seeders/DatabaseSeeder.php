<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\Animal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@jaguza.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create some farmers
        $farmers = User::factory(5)->create(['role' => 'farmer']);

        // Create a farm and animals for each farmer
        foreach ($farmers as $farmer) {
            $farm = Farm::create([
                'user_id' => $farmer->id,
                'name' => $farmer->name . "'s Farm",
                'owner_name' => $farmer->name,
                'location' => 'Kampala, Uganda',
                'is_active' => true,
            ]);

            Animal::factory(10)->create([
                'farm_id' => $farm->id,
                'owner_id' => $farmer->id,
            ]);
        }
    }
}
