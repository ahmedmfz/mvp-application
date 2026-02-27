<?php

namespace Modules\Package\Repositories;

use Modules\Package\Models\Package;

interface PackageRepositoryInterface
{
    public function create(array $data): Package;

    public function update(Package $package, array $data): Package;

    public function delete(Package $package): Package;

    public function findById(int $id): ?Package;

    public function findOrCreateDefault(): Package;
}
