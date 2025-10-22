<?php

namespace App\Http\Requests\Apps;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('apps', 'id')],
            'title' => ['required', 'string', 'max:150'],
            'status' => ['required', Rule::in(['installed', 'running', 'stopped'])],
            'http_port' => ['nullable', 'integer', 'between:1,65535'],
        ];
    }
}
