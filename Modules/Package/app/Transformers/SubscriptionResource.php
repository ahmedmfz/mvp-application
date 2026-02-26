<?php

namespace Modules\Package\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'status'     => $this->status,
            'started_at' => $this->started_at?->toDateTimeString(),
            'ended_at'   => $this->ended_at?->toDateTimeString(),
            'package'    => [
                'id'     => $this->package?->id,
                'name'   => $this->package?->name,
                'price'  => $this->package?->price,
                'status' => $this->package?->status,
            ],
        ];
    }
}
