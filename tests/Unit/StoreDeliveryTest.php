<?php 

namespace Tests\Unit;

use App\Models\Postcode;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreDeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_if_store_delivers_to_postcode(): void
    {
        // Register postcodes used in validation
        Postcode::create([
            'postcode' => 'M11AE',
            'latitude' => 53.480759,
            'longitude' => -2.242631
        ]);

        Postcode::create([
            'postcode' => 'S12HH',
            'latitude' => 53.3811,
            'longitude' =>  -1.4701
        ]);

        // Create a store with a 5km delivery radius
        Store::create([
            'name' => 'Manchester Takeaway',
            'latitude' => 53.480759,
            'longitude' => -2.242631,
            'status' => 'open',
            'store_type' => 'takeaway',
            'max_delivery_distance' => 5
        ]);

        // Test for a postcode within the delivery range
        $response = $this->json('GET', '/api/stores/delivery', [
            'postcode' => 'M11AE',
        ]);

        $response->assertJsonFragment([
            'name' => 'Manchester Takeaway'
        ]);

        // Test for a postcode outside the delivery range
        $response = $this->json('GET', '/api/stores/delivery', [
            'postcode' => 'S105AE',  // Sheffield Postcode
        ]);

        $response->assertJsonMissing([
            'name' => 'Manchester Takeaway',
        ]);
    }
}
