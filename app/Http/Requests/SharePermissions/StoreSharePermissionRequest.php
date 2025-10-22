<?php

namespace App\Http\Requests\SharePermissions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSharePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'share_id' => ['required', 'exists:shares,id'],
            'user_id' => ['required', 'exists:users,id'],
            'perm' => ['required', Rule::in(['read', 'write', 'admin'])],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator): void {
            if (! $validator->errors()->isEmpty()) {
                return;
            }

            $exists = \App\Models\SharePermission::query()
                ->where('share_id', $this->input('share_id'))
                ->where('user_id', $this->input('user_id'))
                ->exists();

            if ($exists) {
                $validator->errors()->add('user_id', __('A megadott felhasználó már rendelkezik jogosultsággal ezen a megosztáson.'));
            }
        });
    }
}
