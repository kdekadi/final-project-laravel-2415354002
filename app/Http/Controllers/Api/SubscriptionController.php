<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class SubscriptionController extends Controller
{
    public function index(): JsonResponse
    {
        $subscriptions = Subscription::query()
            ->with(["customer", "service"])
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Subscriptions retrieved successfully",
            "data" => $subscriptions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "customer_id" => ["required", "integer", "exists:customers,id"],
            "service_id" => ["required", "integer", "exists:services,id"],
            "start_date" => ["nullable", "date"],
            "end_date" => ["nullable", "date", "after_or_equal:start_date"],
            "status" => ["nullable", new Enum(SubscriptionStatus::class)],
        ]);

        $customer = Customer::query()->find($data["customer_id"]);
        if (!$customer || !$customer->status) {
            return response()->json(
                [
                    "success" => false,
                    "message" =>
                        "Customer must be active to create a subscription",
                    "errors" => [],
                ],
                422,
            );
        }

        $service = Service::query()->find($data["service_id"]);
        if (!$service || !$service->status) {
            return response()->json(
                [
                    "success" => false,
                    "message" =>
                        "Service must be active to create a subscription",
                    "errors" => [],
                ],
                422,
            );
        }

        $data["status"] = $data["status"] ?? SubscriptionStatus::ACTIVE->value;

        $subscription = Subscription::query()->create($data);
        $subscription->load(["customer", "service"]);

        return response()->json(
            [
                "success" => true,
                "message" => "Subscription created successfully",
                "data" => $subscription,
            ],
            201,
        );
    }

    public function activate(int $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Subscription not found",
                    "errors" => [],
                ],
                404,
            );
        }

        if ($subscription->status === SubscriptionStatus::DISMANTLE) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Dismantled subscription cannot change status",
                    "errors" => [],
                ],
                422,
            );
        }

        $allowed = [
            SubscriptionStatus::TRIAL,
            SubscriptionStatus::INACTIVE,
            SubscriptionStatus::ISOLIR,
        ];

        if (!in_array($subscription->status, $allowed, true)) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid status transition to active",
                    "errors" => [],
                ],
                422,
            );
        }

        DB::transaction(function () use ($subscription): void {
            $subscription->update([
                "status" => SubscriptionStatus::ACTIVE->value,
            ]);
        });

        $subscription->load(["customer", "service"]);

        return response()->json([
            "success" => true,
            "message" => "Subscription activated successfully",
            "data" => $subscription,
        ]);
    }

    public function deactivate(int $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Subscription not found",
                    "errors" => [],
                ],
                404,
            );
        }

        if ($subscription->status === SubscriptionStatus::DISMANTLE) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Dismantled subscription cannot change status",
                    "errors" => [],
                ],
                422,
            );
        }

        $allowed = [
            SubscriptionStatus::ACTIVE,
            SubscriptionStatus::TRIAL,
            SubscriptionStatus::ISOLIR,
        ];

        if (!in_array($subscription->status, $allowed, true)) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid status transition to inactive",
                    "errors" => [],
                ],
                422,
            );
        }

        DB::transaction(function () use ($subscription): void {
            $subscription->update([
                "status" => SubscriptionStatus::INACTIVE->value,
            ]);
        });

        $subscription->load(["customer", "service"]);

        return response()->json([
            "success" => true,
            "message" => "Subscription deactivated successfully",
            "data" => $subscription,
        ]);
    }

    public function trial(int $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Subscription not found",
                    "errors" => [],
                ],
                404,
            );
        }

        if ($subscription->status === SubscriptionStatus::DISMANTLE) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Dismantled subscription cannot change status",
                    "errors" => [],
                ],
                422,
            );
        }

        $allowed = [
            SubscriptionStatus::ACTIVE,
            SubscriptionStatus::INACTIVE,
            SubscriptionStatus::ISOLIR,
        ];

        if (!in_array($subscription->status, $allowed, true)) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid status transition to trial",
                    "errors" => [],
                ],
                422,
            );
        }

        DB::transaction(function () use ($subscription): void {
            $subscription->update([
                "status" => SubscriptionStatus::TRIAL->value,
            ]);
        });

        $subscription->load(["customer", "service"]);

        return response()->json([
            "success" => true,
            "message" => "Subscription set to trial successfully",
            "data" => $subscription,
        ]);
    }

    public function isolir(int $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Subscription not found",
                    "errors" => [],
                ],
                404,
            );
        }

        if ($subscription->status === SubscriptionStatus::DISMANTLE) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Dismantled subscription cannot change status",
                    "errors" => [],
                ],
                422,
            );
        }

        if ($subscription->status !== SubscriptionStatus::ACTIVE) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid status transition to isolir",
                    "errors" => [],
                ],
                422,
            );
        }

        DB::transaction(function () use ($subscription): void {
            $subscription->update([
                "status" => SubscriptionStatus::ISOLIR->value,
            ]);
        });

        $subscription->load(["customer", "service"]);

        return response()->json([
            "success" => true,
            "message" => "Subscription isolated successfully",
            "data" => $subscription,
        ]);
    }

    public function dismantle(int $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Subscription not found",
                    "errors" => [],
                ],
                404,
            );
        }

        if ($subscription->status === SubscriptionStatus::DISMANTLE) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Dismantled subscription cannot change status",
                    "errors" => [],
                ],
                422,
            );
        }

        DB::transaction(function () use ($subscription): void {
            $subscription->update([
                "status" => SubscriptionStatus::DISMANTLE->value,
            ]);
        });

        $subscription->load(["customer", "service"]);

        return response()->json([
            "success" => true,
            "message" => "Subscription dismantled successfully",
            "data" => $subscription,
        ]);
    }
}