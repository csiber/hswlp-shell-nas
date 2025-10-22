<?php

namespace App\Http\Requests\AppInstances;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppInstanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'app_id' => ['required', 'exists:apps,id'],
            'name' => ['required', 'string', 'max:100'],
            'bind_path' => ['required', 'string', 'max:255'],
            'env_json' => ['nullable', 'array'],
            'status' => ['required', Rule::in(['running', 'stopped'])],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $exists = \App\Models\AppInstance::query()
                ->where('app_id', $this->input('app_id'))
                ->where('name', $this->input('name'))
                ->exists();

            if ($exists) {
                $validator->errors()->add('name', __('Ezzel a névvel már létezik példány az adott alkalmazáshoz.'));
            }
        });
    }
}
