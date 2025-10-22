<?php

namespace App\Http\Requests\Shares;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShareRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $shareId = $this->route('share')?->getKey();

        return [
            'name' => ['sometimes', 'required', 'string', 'max:100', Rule::unique('shares', 'name')->ignore($shareId)],
            'path' => ['sometimes', 'required', 'string', 'max:255'],
            'smb_export' => ['sometimes', 'boolean'],
            'nfs_export' => ['sometimes', 'boolean'],
            'quota_gb' => ['nullable', 'integer', 'min:1'],
            'owner_user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
