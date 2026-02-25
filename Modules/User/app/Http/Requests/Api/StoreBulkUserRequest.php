<?php

namespace Modules\User\Http\Requests\Api;

use App\Http\Requests\BaseApiRequest;


class StoreBulkUserRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'users' => ['required', 'array', 'min:1', 'max:50000'],

            'users.*.name'  => ['required', 'string', 'min:2', 'max:255'],
            'users.*.email' => ['required', 'email:rfc,dns', 'max:255', 'distinct'], 
        ];
    }

    public function messages(): array
    {
        return [
            'users.*.email.distinct' => 'Duplicate email found in request payload.',
        ];
    }

}
