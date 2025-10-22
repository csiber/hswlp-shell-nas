<?php

namespace App\Http\Requests\SharePermissions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSharePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'perm' => ['required', Rule::in(['read', 'write', 'admin'])],
        ];
    }
}
