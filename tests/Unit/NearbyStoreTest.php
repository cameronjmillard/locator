<?php 

namespace Tests\Unit;

use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NearbyStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_nearby_stores(): void
    {
        // Create a store located at Manchester (M1 1AE)
        Store::create([
            'name' => 'Manchester Takeaway',
            'latitude' => 53.480759,
            'longitude' => -2.242631,
            'status' => 'open',
            'store_type' => 'takeaway',
            'max_delivery_distance' => 5,
        ]);

        // Create another store further away from the test location
        Store::create([
            'name' => 'Sheffield Restaurant',
            'latitude' => 53.3811,
            'longitude' => -1.4701,
            'status' => 'open',
            'store_type' => 'restaurant',
            'max_delivery_distance' => 10,
        ]);

        // Call the nearby stores API endpoint (or method)
        $response = $this->json('GET', '/api/stores/nearby', [
            'latitude' => 53.480759,
            'longitude' => -2.242631,
            'radius' => 10,
        ]);

        $response->assertJsonFragment([
            'name' => 'Manchester Takeaway',
        ]);

        $response->assertJsonMissing([
            'name' => 'Salford Cafe',
        ]);
    }
}
