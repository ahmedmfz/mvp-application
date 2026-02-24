<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\HelperResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Modules\Users\Http\Requests\Api\StoreUserRequest;
use Modules\Users\Jobs\RecordOnboardingActivity;
use Modules\Users\Jobs\SendUserCreatedNotification;
use Modules\Users\Jobs\UpdateDailyOnboardingStats;
use Modules\Users\Models\User;
use Modules\Users\Transformers\UserResource;
use OpenApi\Attributes as OA;


class UsersController extends Controller
{
    #[OA\Post(
        path: '/v1/users',
        tags: ['Users'],
        summary: 'Create a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name',     type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email',    type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreUserRequest $request)
    {       
        $user = User::create($request->validated());

        Bus::chain([
            new SendUserCreatedNotification($user),
            new RecordOnboardingActivity($user),
            new UpdateDailyOnboardingStats($user),
        ])->catch(function (\Throwable $e) use ($user) {
            logger()->error('User onboarding chain failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        })->dispatch();

        return HelperResponse::success(new UserResource($user), 'User created successfully', 201);
    }

    #[OA\Get(
        path: '/v1/users/{id}',
        tags: ['Users'],
        summary: 'Get a user by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(User $user)
    {
        return HelperResponse::success(new UserResource($user));
    }

    #[OA\Put(
        path: '/v1/users/{id}',
        tags: ['Users'],
        summary: 'Update a user',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name',  type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update(Request $request, User $user) {
        $user->update($request->validated());
        return HelperResponse::success(new UserResource($user), 'User updated successfully');
    }

    #[OA\Delete(
        path: '/v1/users/{id}',
        tags: ['Users'],
        summary: 'Delete a user',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Deleted'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(User $user) {
        $user->delete();
        return HelperResponse::success(null, 'User deleted successfully');
    }
}
