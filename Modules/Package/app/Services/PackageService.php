<?php

namespace Modules\Package\Services;

use Modules\Package\Models\Package;
use Modules\Package\Repositories\PackageRepositoryInterface;

class PackageService
{
    public function __construct(
        public PackageRepositoryInterface $packageRepository
    ) {}

    public function store(array $data): Package
    {
        return $this->packageRepository->create($data);
    }

    public function findById(int $id): ?Package
    {
        return $this->packageRepository->findById($id);
    }

    public function update(array $data, Package $package): Package
    {
        return $this->packageRepository->update($package, $data);
    }

    public function destroy(Package $package): Package
    {
        return $this->packageRepository->delete($package);
    }
}
