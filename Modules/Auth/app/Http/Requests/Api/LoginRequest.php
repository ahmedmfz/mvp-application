<?php

namespace Modules\Auth\Http\Requests\Api;

use App\Http\Requests\BaseApiRequest;

class LoginRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];
    }
}
