<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::name("api.")->group(function () {
    Route::apiResource("customers", CustomerController::class);

    Route::patch("customers/{customer}/activate", [
        CustomerController::class,
        "activate",
    ]);

    Route::patch("customers/{customer}/deactivate", [
        CustomerController::class,
        "deactivate",
    ]);

    Route::apiResource("services", ServiceController::class);

    Route::patch("services/{service}/activate", [
        ServiceController::class,
        "activate",
    ]);

    Route::patch("services/{service}/deactivate", [
        ServiceController::class,
        "deactivate",
    ]);

    Route::apiResource("subscriptions", SubscriptionController::class)->only([
        "index",
        "store",
    ]);

    Route::patch("subscriptions/{subscription}/activate", [
        SubscriptionController::class,
        "activate",
    ]);

    Route::patch("subscriptions/{subscription}/deactivate", [
        SubscriptionController::class,
        "deactivate",
    ]);

    Route::patch("subscriptions/{subscription}/trial", [
        SubscriptionController::class,
        "trial",
    ]);

    Route::patch("subscriptions/{subscription}/isolir", [
        SubscriptionController::class,
        "isolir",
    ]);

    Route::patch("subscriptions/{subscription}/dismantle", [
        SubscriptionController::class,
        "dismantle",
    ]);
});