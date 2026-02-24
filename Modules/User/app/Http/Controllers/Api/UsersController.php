<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\HelperResponse;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\Api\StoreUserRequest;
use Modules\User\Http\Requests\Api\UpdateUserRequest;
use Modules\User\Models\User;
use Modules\User\Transformers\UserResource;
use Modules\User\Services\UserService;
use OpenApi\Attributes as OA;


class UsersController extends Controller
{
    public function __construct(public UserService $userService){}

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
        $user = $this->userService->store($request->validated());
        return HelperResponse::success(new UserResource($user) , 'User created successfully', 201);
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
    public function update(UpdateUserRequest $request, User $user) {
        $user = $this->userService->update($request->validated(), $user);
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
        $user = $this->userService->destroy($user);
        return HelperResponse::success(new UserResource($user), 'User deleted successfully');
    }
}
