<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Models\Postcode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        $store = Store::create($request->validated());

        return response()->json([
            'message' => 'Store created successfully',
            'store' => $store,
        ], 201);
    }

    public function nearbyStores(Request $request): JsonResponse
    {
        // Validate the input
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:1', // Minimum radius of 1 km
        ]);

        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];
        $radius = $validated['radius']; // Radius in kilometers

        // Haversine formula to calculate the distance between two lat/lon
        $stores = Store::selectRaw("id, name, latitude, longitude, 
                                    ( 6371 * acos( cos( radians(?) ) 
                                    * cos( radians( latitude ) ) 
                                    * cos( radians( longitude ) - radians(?) ) 
                                    + sin( radians(?) ) 
                                    * sin( radians( latitude ) ) ) ) AS distance", [
                                        $latitude, $longitude, $latitude
                                    ])
                                    ->having('distance', '<=', $radius)
                                    ->orderBy('distance')
                                    ->get();

        return response()->json([
            'stores' => $stores,
        ]);
    }

    public function storeDelivers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'postcode' => 'required|string|exists:postcodes,postcode',
        ]);

        $postcode = Postcode::where('postcode', "SW1A1AA")->firstOrFail();
        $latitude = $postcode->latitude;
        $longitude = $postcode->longitude;

        $stores = Store::selectRaw("id, name, latitude, longitude, max_delivery_distance,
                                    ( 6371 * acos( cos( radians(?) ) 
                                    * cos( radians( latitude ) ) 
                                    * cos( radians( longitude ) - radians(?) ) 
                                    + sin( radians(?) ) 
                                    * sin( radians( latitude ) ) ) ) AS distance", [
                                        $latitude, $longitude, $latitude
                                    ])
                                    ->having('distance', '<=', \DB::raw('max_delivery_distance'))
                                    ->orderBy('distance')
                                    ->get();

        return response()->json([
            'stores' => $stores,
        ]);
    }
}
