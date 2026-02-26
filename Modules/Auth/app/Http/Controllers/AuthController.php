<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Service\HelperResponse;
use Modules\Auth\Http\Requests\Api\LoginRequest;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Transformers\AuthResource;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService) {}

    #[OA\Post(
        path: '/v1/auth/login',
        tags: ['Auth'],
        summary: 'Login and receive an API token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email',    type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token',      type: 'string', example: 'abc123...'),
                        new OA\Property(
                            property: 'user',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id',    type: 'integer'),
                                new OA\Property(property: 'name',  type: 'string'),
                                new OA\Property(property: 'email', type: 'string'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid credentials'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function login(LoginRequest $request)
    {
        $user = $this->authService->login($request->validated());

        if (! $user) {
            return HelperResponse::error(null, 'Invalid credentials', 401);
        }

        return HelperResponse::success(new AuthResource($user), 'Login successful');
    }
}
