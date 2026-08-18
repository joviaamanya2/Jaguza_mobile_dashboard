<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FarmImageUploadTest extends TestCase
{
    public function test_farm_can_be_created_with_an_uploaded_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'farmer',
            'is_active' => true,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/farms', [
                'name' => 'Green Valley Farm',
                'location' => 'Wakiso, Uganda',
                'owner_name' => 'John Mukasa',
                'size' => '50 acres',
                'description' => 'A modern dairy farm',
                'established_year' => '2018',
                'coordinates' => '0.3476, 32.5825',
                'facilities' => ['Barn', 'Water Tanks'],
                'image' => UploadedFile::fake()->image('farm.jpg'),
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('farms', [
            'user_id' => $user->id,
            'name' => 'Green Valley Farm',
            'owner_name' => 'John Mukasa',
        ]);

        $farm = $user->farms()->latest()->first();
        $this->assertNotNull($farm->image);
        $this->assertTrue(Storage::disk('public')->exists($farm->image));
    }
}
