<?php

namespace App\Http\Requests\Apps;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:150'],
            'status' => ['sometimes', 'required', Rule::in(['installed', 'running', 'stopped'])],
            'http_port' => ['nullable', 'integer', 'between:1,65535'],
        ];
    }
}
