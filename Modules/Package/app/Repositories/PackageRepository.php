<?php

namespace Modules\Package\Repositories;


use Modules\Package\Models\Package;


class PackageRepository implements PackageRepositoryInterface
{
    public function all()
    {
        return Package::get();
    }

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

    public function updateDefaultFalse(): void
    {
        Package::where('is_default', true)->update(['is_default' => false]);
    }

    public function findOrCreateDefault(): Package
    {
        return Package::firstOrCreate(
            ['is_default' => true],
            [
                'name'        => 'Basic',
                'description' => 'Default free package assigned to all new users.',
                'price'       => 0.00,
                'status'      => 'active',
                'is_default'  => true,
            ]
        );
    }
}
