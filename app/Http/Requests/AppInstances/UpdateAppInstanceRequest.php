<?php

namespace App\Http\Requests\AppInstances;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppInstanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'bind_path' => ['sometimes', 'required', 'string', 'max:255'],
            'env_json' => ['nullable', 'array'],
            'status' => ['sometimes', 'required', Rule::in(['running', 'stopped'])],
        ];
    }
}
