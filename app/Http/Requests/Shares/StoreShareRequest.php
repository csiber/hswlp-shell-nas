<?php

namespace App\Http\Requests\Shares;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShareRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('shares', 'name')],
            'path' => ['required', 'string', 'max:255'],
            'smb_export' => ['sometimes', 'boolean'],
            'nfs_export' => ['sometimes', 'boolean'],
            'quota_gb' => ['nullable', 'integer', 'min:1'],
            'owner_user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
