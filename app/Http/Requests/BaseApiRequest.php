<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Service\HelperResponse;

abstract class BaseApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Modules override this as needed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules must be defined by each concrete request class.
     */
    abstract public function rules(): array;

    /**
     * Override the validation failure response to match the unified
     * HelperResponse::error() format used across the whole API.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            HelperResponse::error(
                $validator->errors()->first(),
                422
            )
        );
    }
}
