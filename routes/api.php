<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeServiceController;
use App\Http\Controllers\Api\BookingTransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//route custom agar show method bisa menerima slug home service
Route::get('/homeService/{homeService:slug}', [HomeServiceController::class, 'show']);
//route api untuk house services
Route::apiResource('/services', HomeServiceController::class);

//route custom agar show method bisa menerima slug category
Route::get('/category/{category:slug}', [CategoryController::class, 'show']);
//route api untuk category
Route::apiResource('/categories', CategoryController::class);

//route untuk booking transaction store (membuat booking home service)
Route::post('/booking-transaction', [BookingTransactionController::class, 'store']);

//route untuk check my booking
Route::post('/check-booking', [BookingTransactionController::class, 'booking_details']);
