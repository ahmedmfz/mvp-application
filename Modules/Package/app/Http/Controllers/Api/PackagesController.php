<?php

namespace Modules\Package\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\HelperResponse;
use Modules\Package\Http\Requests\Api\StorePackageRequest;
use Modules\Package\Http\Requests\Api\UpdatePackageRequest;
use Modules\Package\Models\Package;
use Modules\Package\Transformers\PackageResource;
use Modules\Package\Services\PackageService;
use OpenApi\Attributes as OA;

class PackagesController extends Controller
{
    public function __construct(public PackageService $packageService) {}

    #[OA\Get(
        path: '/v1/packages',
        tags: ['Packages'],
        summary: 'Get all packages (admin only)',
        security: [['api_key' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden – admin only'),
        ]
    )]
    public function index()
    {
        $packages = $this->packageService->index();
        return HelperResponse::success(PackageResource::collection($packages));
    }

    #[OA\Post(
        path: '/v1/packages',
        tags: ['Packages'],
        summary: 'Create a new package (admin only)',
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'price'],
                properties: [
                    new OA\Property(property: 'name',        type: 'string',  example: 'Basic Plan'),
                    new OA\Property(property: 'description', type: 'string',  example: 'Entry level plan'),
                    new OA\Property(property: 'price',       type: 'number',  format: 'float', example: 9.99),
                    new OA\Property(property: 'status',      type: 'string',  enum: ['active', 'inactive'], example: 'active'),
                    new OA\Property(property: 'is_default',  type: 'boolean', example: false),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden – admin only'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StorePackageRequest $request)
    {
        $package = $this->packageService->store($request->validated());
        return HelperResponse::success(new PackageResource($package), 'Package created successfully', 201);
    }

    #[OA\Get(
        path: '/v1/packages/{id}',
        tags: ['Packages'],
        summary: 'Get a package by ID (admin only)',
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden – admin only'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(Package $package)
    {
        return HelperResponse::success(new PackageResource($package));
    }

    #[OA\Put(
        path: '/v1/packages/{id}',
        tags: ['Packages'],
        summary: 'Update a package (admin only)',
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name',        type: 'string'),
                    new OA\Property(property: 'description', type: 'string'),
                    new OA\Property(property: 'price',       type: 'number', format: 'float'),
                    new OA\Property(property: 'status',      type: 'string', enum: ['active', 'inactive']),
                    new OA\Property(property: 'is_default',  type: 'boolean', example: false),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden – admin only'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update(UpdatePackageRequest $request, Package $package)
    {
        $package = $this->packageService->update($request->validated(), $package);
        return HelperResponse::success(new PackageResource($package), 'Package updated successfully');
    }

    #[OA\Delete(
        path: '/v1/packages/{id}',
        tags: ['Packages'],
        summary: 'Delete a package (admin only)',
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden – admin only'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(Package $package)
    {
        $package = $this->packageService->destroy($package);
        return HelperResponse::success(new PackageResource($package), 'Package deleted successfully');
    }
}
