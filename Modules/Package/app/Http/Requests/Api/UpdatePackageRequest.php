<?php

namespace Modules\Package\Http\Requests\Api;

use App\Http\Requests\BaseApiRequest;

class UpdatePackageRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'status'      => 'sometimes|in:active,inactive',
            'is_default'  => 'sometimes|boolean',
        ];
    }
}
