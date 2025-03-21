<?php

namespace Tests\Unit;

use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_store(): void
    {
        $storeData = [
            'name' => 'Manchester Takeaway',
            'latitude' => 53.480759,
            'longitude' => -2.242631,
            'status' => 'open',
            'store_type' => 'takeaway',
            'max_delivery_distance' => 5
        ];

        $store = Store::create($storeData);

        $this->assertDatabaseHas('stores', [
            'name' => 'Manchester Takeaway',
            'latitude' => 53.480759,
            'longitude' => -2.242631,
            'status' => 'open',
            'store_type' => 'takeaway',
            'max_delivery_distance' => 5
        ]);
    }
}
