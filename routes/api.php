<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\Api\AdminOrdersController;
use App\Http\Controllers\Api\AmenityController;
use App\Http\Controllers\Api\BadgeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\FinanceInquiryController;
use App\Http\Controllers\Api\GarageController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InspectionRequestController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\ListingBrandController;
// use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserProfileController;

Route::post('/profile/update', [UserProfileController::class, 'update']);
Route::post('/profile/delete', [UserProfileController::class, 'delete']);
// Route::post('/get-profile', [UserProfileController::class, 'profile']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/listings', [ListingController::class, 'index']);
Route::get('/brands', [ListingBrandController::class, 'index']);
Route::post('/get-profile', [UserProfileController::class, 'profile']);





Route::post('inspection-request/{listingId}', [InspectionRequestController::class, 'requestInspection'])->name('inspection.request');

// API Routes (for retrieving and updating requests)
Route::get('api/seller/inspection-requests', [InspectionRequestController::class, 'sellerRequests']);
Route::put('api/inspection-request/{id}/status/{status}', [InspectionRequestController::class, 'updateStatus']);


Route::get('home', [HomeController::class, 'index']);

Route::get('garages', [GarageController::class, 'index']);
Route::post('garages', [GarageController::class, 'store']);
Route::post('garages/{garageId}/services', [GarageController::class, 'addService']);


Route::post('submit-inquiry/{listingId}', [FinanceInquiryController::class, 'submitInquiry']);
Route::get('admin-inquiries', [FinanceInquiryController::class, 'adminInquiries']);


Route::get('faqs', [FaqController::class, 'index']);

Route::get('category/{slug}', [CategoryController::class, 'detail']);

Route::get('badges', [BadgeController::class, 'index']);
Route::get('badge/{id}', [BadgeController::class, 'edit']);
Route::post('badge', [BadgeController::class, 'store']);
Route::put('badge/{id}', [BadgeController::class, 'update']);
Route::delete('badge/{id}', [BadgeController::class, 'destroy']);
Route::post('badge/{id}/status', [BadgeController::class, 'change_status']);


Route::get('amenities', [AmenityController::class, 'index']);
Route::get('amenity/{id}', [AmenityController::class, 'edit']);
Route::post('amenity', [AmenityController::class, 'store']);
Route::put('amenity/{id}', [AmenityController::class, 'update']);
Route::delete('amenity/{id}', [AmenityController::class, 'destroy']);

// Route for fetching all orders (index method)
Route::get('admin/orders', [AdminOrdersController::class, 'index'])->name('admin.orders.index');

// Route for approving an order (approve method)
Route::put('admin/orders/{order}/approve', [AdminOrdersController::class, 'approve'])->name('admin.orders.approve');

// Route for rejecting an order (reject method)
Route::put('admin/orders/{order}/reject', [AdminOrdersController::class, 'reject'])->name('admin.orders.reject');

Route::get('terms-and-conditions', [TermController::class,'index'])
    ->name('front_terms_and_conditions');
