<?php

namespace App\Services;

use App\Models\Share;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ShareService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Share::query()
            ->with(['owner'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return Share::with('owner')->orderBy('name')->get();
    }

    public function create(array $data): Share
    {
        return Share::create($data);
    }

    public function update(Share $share, array $data): Share
    {
        $share->fill($data);
        $share->save();

        return $share->refresh();
    }

    public function delete(Share $share): void
    {
        $share->delete();
    }
}
