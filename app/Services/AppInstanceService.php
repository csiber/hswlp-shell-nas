<?php

namespace App\Services;

use App\Models\AppInstance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AppInstanceService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return AppInstance::query()
            ->with('app')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function create(array $data): AppInstance
    {
        return AppInstance::create($data);
    }

    public function update(AppInstance $instance, array $data): AppInstance
    {
        $instance->fill($data);
        $instance->save();

        return $instance->refresh();
    }

    public function delete(AppInstance $instance): void
    {
        $instance->delete();
    }
}
