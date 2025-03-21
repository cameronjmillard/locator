<?php

use App\Http\Controllers\StoreController;

Route::post('/stores', [StoreController::class, 'store']);
Route::get('/stores/nearby', [StoreController::class, 'nearbyStores']);
Route::get('/stores/delivery', [StoreController::class, 'storeDelivers']);

