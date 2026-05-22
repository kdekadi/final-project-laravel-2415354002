<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(): JsonResponse
    {
        $subscriptions = Subscription::with(['customer', 'service'])->latest()->get();

        return response()->json([
            "success" => true,
            "message" => "Subscriptions retrieved successfully",
            "data" => $subscriptions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "customer_id" => ["required", "exists:customers,id"], 
            "service_id" => ["required", "exists:services,id"],  
            "start_date" => ["required", "date"],
            "end_date" => ["nullable", "date", "after_or_equal:start_date"],
            "status" => ["nullable", "in:active,expired,cancelled"],
        ]);

        $data["status"] = $data["status"] ?? "active";

        $subscription = Subscription::query()->create($data);

        $subscription->load(['customer', 'service']);

        return response()->json([
            "success" => true,
            "message" => "Subscription created successfully",
            "data" => $subscription,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $subscription = Subscription::query()->find($id);

        if (!$subscription) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        $data = $request->validate([
            "status" => ["required", "in:active,expired,cancelled"],
            "end_date" => ["nullable", "date"]
        ]);

        $subscription->update($data);
        $subscription->load(['customer', 'service']);

        return response()->json([
            "success" => true,
            "message" => "Subscription status updated successfully",
            "data" => $subscription,
        ]);
    }
}