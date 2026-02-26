<?php

namespace Modules\Package\Http\Requests\Api;

use App\Http\Requests\BaseApiRequest;

class SubscribeRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'package_id' => 'required|integer|exists:packages,id',
        ];
    }
}
