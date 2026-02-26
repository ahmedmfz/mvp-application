<?php

namespace Modules\Package\Http\Requests\Api;

use App\Http\Requests\BaseApiRequest;

class StorePackageRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'sometimes|in:active,inactive',
        ];
    }
}
