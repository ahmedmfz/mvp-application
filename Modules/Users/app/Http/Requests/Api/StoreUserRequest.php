<?php

namespace Modules\Users\Http\Requests\Api;

use App\Http\Requests\BaseApiRequest;


class StoreUserRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];
    }

}
