<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Package\Models\Package;

class UserResource extends JsonResource
{
    // Pre-loaded once by the service/controller before serialization begins.
    // The resource itself NEVER queries the DB for the default package.
    private static ?Package $defaultPackage = null;

    public static function setDefaultPackage(?Package $package): void
    {
        static::$defaultPackage = $package;
    }

    public function toArray(Request $request): array
    {
        $data = [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
        ];

        if ($this->isConsumer()) {
            $package = $this->activePackage->first() ?? static::$defaultPackage;

            $data['current_package'] = $package ? [
                'name'  => $package->name,
                'price' => $package->price,
            ] : null;
        }

        return $data;
    }
}
