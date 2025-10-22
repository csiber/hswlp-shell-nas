<?php

namespace App\Services;

use App\Models\SharePermission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class SharePermissionService
{
    public function paginate(int $perPage = 15, ?string $shareId = null): LengthAwarePaginator
    {
        return SharePermission::query()
            ->when($shareId, fn (Builder $query) => $query->where('share_id', $shareId))
            ->with(['share', 'user'])
            ->orderBy('share_id')
            ->paginate($perPage);
    }

    public function create(array $data): SharePermission
    {
        return SharePermission::create($data);
    }

    public function update(SharePermission $permission, array $data): SharePermission
    {
        $permission->fill($data);
        $permission->save();

        return $permission->refresh();
    }

    public function delete(SharePermission $permission): void
    {
        $permission->delete();
    }
}
