<?php

namespace Modules\User\Http\Requests\Api;

use App\Http\Requests\BaseApiRequest;


class UpdateUserRequest extends BaseApiRequest
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'sometimes|string|min:8',
            'type'     => 'sometimes|in:admin,consumer',
        ];
    }

}
