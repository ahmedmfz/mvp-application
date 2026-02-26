<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'token'      => $this->getToken($this->type),
            'user'       => [
                'id'    => $this->id,
                'name'  => $this->name,
                'email' => $this->email,
            ],
        ];
    }
}
