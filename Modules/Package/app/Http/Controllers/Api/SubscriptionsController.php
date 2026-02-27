<?php

namespace Modules\Package\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\HelperResponse;
use Modules\Package\Http\Requests\Api\SubscribeRequest;
use Modules\Package\Services\SubscriptionService;
use Modules\Package\Transformers\SubscriptionResource;
use OpenApi\Attributes as OA;

class SubscriptionsController extends Controller
{
    public function __construct(public SubscriptionService $subscriptionService) {}

    #[OA\Post(
        path: '/v1/subscriptions',
        tags: ['Subscriptions'],
        summary: 'Subscribe to a package',
        description: 'Subscribes the authenticated user to the given package. Any currently active subscription is automatically cancelled and recorded in history.',
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['package_id'],
                properties: [
                    new OA\Property(property: 'package_id', type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Already subscribed to this package (no change made)'),
            new OA\Response(response: 201, description: 'Subscribed successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function subscribe(SubscribeRequest $request)
    {
        $result = $this->subscriptionService->subscribe(
            userId:    auth()->id(),
            packageId: $request->validated('package_id'),
        );

        if ($result['already_active']) {
            return HelperResponse::success(
                new SubscriptionResource($result['subscription']->load('package')),
                'Already subscribed to this package',
                200
            );
        }

        return HelperResponse::success(
            new SubscriptionResource($result['subscription']->load('package')),
            'Subscribed successfully',
            201
        );
    }

    #[OA\Get(
        path: '/v1/subscriptions',
        tags: ['Subscriptions'],
        summary: 'Get subscription history',
        description: 'Returns the full subscription history of the authenticated user, newest first. The currently active subscription has `ended_at: null`.',
        security: [['api_key' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function history()
    {
        $history = $this->subscriptionService->getHistory(auth()->id());

        return HelperResponse::success(SubscriptionResource::collection($history));
    }
}
