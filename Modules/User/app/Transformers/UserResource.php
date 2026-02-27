<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $package = $this->activePackage->first();

        $data = [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
        ];

        if ($this->isConsumer()) {
            $data['current_package'] = $package ? [
                'name'  => $package->name,
                'price' => $package->price,
            ] : null;
        }

        return $data;
    }
}
