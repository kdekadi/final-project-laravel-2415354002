<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung lempar ke customers agar tidak welcome screen default
Route::get("/", function () {
    return redirect()->route("customers.index");
});

// ==========================================
// 1. ROUTE LENGKAP MANAGEMENT CUSTOMERS
// ==========================================
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get("/", [CustomerController::class, "index"])->name("index");
    Route::post("/", [CustomerController::class, "store"])->name("store");
    Route::patch("/{id}", [CustomerController::class, "update"])->name("update");
    Route::delete("/{id}", [CustomerController::class, "destroy"])->name("destroy");
    Route::patch("/{id}/activate", [CustomerController::class, "activate"])->name("activate");
    Route::patch("/{id}/deactivate", [CustomerController::class, "deactivate"])->name("deactivate");
});

// ==========================================
// 2. ROUTE LENGKAP SERVICES PACKAGE
// ==========================================
Route::prefix('services')->name('services.')->group(function () {
    Route::get("/", [ServiceController::class, "index"])->name("index");
    Route::post("/", [ServiceController::class, "store"])->name("store");
    Route::patch("/{id}", [ServiceController::class, "update"])->name("update");
    Route::delete("/{id}", [ServiceController::class, "destroy"])->name("destroy");
    Route::patch("/{id}/activate", [ServiceController::class, "activate"])->name("activate");
    Route::patch("/{id}/deactivate", [ServiceController::class, "deactivate"])->name("deactivate");
});

// ==========================================
// 3. ROUTE LENGKAP SUBSCRIPTIONS (ADD & READ)
// ==========================================
Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
    Route::get("/", [SubscriptionController::class, "index"])->name("index");
    Route::post("/", [SubscriptionController::class, "store"])->name("store");
    Route::patch("/{id}/activate", [SubscriptionController::class, "activate"])->name("activate");
    Route::patch("/{id}/deactivate", [SubscriptionController::class, "deactivate"])->name("deactivate");
    Route::patch("/{id}/trial", [SubscriptionController::class, "trial"])->name("trial");
    Route::patch("/{id}/isolir", [SubscriptionController::class, "isolir"])->name("isolir");
    Route::patch("/{id}/dismantle", [SubscriptionController::class, "dismantle"])->name("dismantle");
});