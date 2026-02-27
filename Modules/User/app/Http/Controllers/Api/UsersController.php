<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\HelperResponse;
use Illuminate\Http\Request;
use Modules\Package\Models\Package;
use Modules\User\Http\Requests\Api\StoreUserRequest;
use Modules\User\Http\Requests\Api\UpdateUserRequest;
use Modules\User\Http\Requests\Api\StoreBulkUserRequest;
use Modules\User\Models\User;
use Modules\User\Transformers\UserCollection;
use Modules\User\Transformers\UserResource;
use Modules\User\Services\UserService;
use Modules\User\Services\BulkUserOnboardingService;
use Modules\Package\Services\PackageService;
use OpenApi\Attributes as OA;


class UsersController extends Controller
{
    public function __construct(
        public UserService $userService, 
        public BulkUserOnboardingService $bulkUserOnboardingService,
        public PackageService $packageService
    ){}

    #[OA\Get(
        path: '/v1/users',
        tags: ['Users'],
        summary: 'List consumers',
        description: 'Returns a paginated list of consumer users with their current active package.',
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(name: 'page',     in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 15)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);
        $users   = $this->userService->getAll($perPage);
        UserResource::setDefaultPackage(
            $this->packageService->getDefaultPackage()
        );
        return HelperResponse::success(new UserCollection($users));
    }

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
                    new OA\Property(property: 'type',     type: 'string', enum: ['admin', 'consumer'], example: 'consumer'),
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
        UserResource::setDefaultPackage($this->packageService->getDefaultPackage());
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
        $user->load('activePackage');
        UserResource::setDefaultPackage($this->packageService->getDefaultPackage());
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
        $user->load('activePackage');
        UserResource::setDefaultPackage($this->packageService->getDefaultPackage());
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

    #[OA\Post(
        path: '/v1/users/bulk',
        tags: ['Users'],
        summary: 'Create multiple users at once',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'array',
                items: new OA\Items(
                    required: ['name', 'email', 'password'],
                    properties: [
                        new OA\Property(property: 'name',     type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email',    type: 'string', format: 'email', example: 'john@example.com'),
                        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function storeBulk(StoreBulkUserRequest $request)
    {
        $result = $this->bulkUserOnboardingService->startBulk($request->validated()['users']);
        return HelperResponse::success([
            'import_id' => $result['import_id'],
            'batch_id'  => $result['batch_id'],
        ], 'Bulk onboarding started', 201);
    }   


    //for testing
    public function createUsersJson()
    {
        $users = [];

        for ($i = 1; $i <= 1000; $i++) {
            $users[] = [
                'name' => "User {$i}",
                'email' => "bulkUser{$i}@gmail.com",
                'password' => 'password123',
            ];
        }

        file_put_contents(storage_path('users.json'), json_encode(["users"=>$users], JSON_PRETTY_PRINT));

        return response()->json(['message' => 'users.json created']);
    }
}
