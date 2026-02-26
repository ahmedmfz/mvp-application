<?php

namespace Modules\Package\Repositories;

use Modules\Package\Models\Package;

class PackageRepository implements PackageRepositoryInterface
{
    public function create(array $data): Package
    {
        return Package::create($data);
    }

    public function update(Package $package, array $data): Package
    {
        $package->update($data);
        return $package->fresh();
    }

    public function delete(Package $package): Package
    {
        $package->delete();
        return $package;
    }

    public function findById(int $id): ?Package
    {
        return Package::find($id);
    }
}
