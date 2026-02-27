<?php

namespace Modules\Package\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Package\Models\Package;
use Modules\Package\Repositories\PackageRepositoryInterface;


class PackageService
{
    public function __construct(
        public PackageRepositoryInterface $packageRepository
    ) {}

    public function index()
    {
        return Cache::remember('packages_all', now()->addHours(24), function () {
            return $this->packageRepository->all();
        });
    }

    public function getDefaultPackage(): Package
    {
        return Cache::remember('default_package', now()->addHours(24), function () {
            return $this->packageRepository->findOrCreateDefault();
        });
    }
    public function store(array $data): Package
    {
        if(isset($data['is_default']) && $data['is_default'] == true){
            $this->packageRepository->updateDefaultFalse();
        }
        $package = $this->packageRepository->create($data);
        Cache::forget('packages_all');
        Cache::forget('default_package');
        
        return $package;
    }

    public function findById(int $id): ?Package
    {
        return $this->packageRepository->findById($id);
    }

    public function update(array $data, Package $package): Package
    {
        if(isset($data['is_default']) && $data['is_default'] == true){
            $this->packageRepository->updateDefaultFalse();
        }
        $updatedPackage = $this->packageRepository->update($package, $data);
        Cache::forget('packages_all');
        Cache::forget('default_package');
        
        return $updatedPackage;
    }

    public function destroy(Package $package): Package
    {
        $deletedPackage = $this->packageRepository->delete($package);
        Cache::forget('packages_all');
        Cache::forget('default_package');
        
        return $deletedPackage;
    }
}
