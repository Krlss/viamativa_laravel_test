<?php

namespace App\Http\Requests;

use App\Models\Hall;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HallUpdateRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  Hall::$rulesUpdate;
    }
}
