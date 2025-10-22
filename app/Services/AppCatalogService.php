<?php

namespace App\Services;

use App\Models\App as AppModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AppCatalogService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return AppModel::query()
            ->withCount('instances')
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function create(array $data): AppModel
    {
        return AppModel::create($data);
    }

    public function update(AppModel $app, array $data): AppModel
    {
        $app->fill($data);
        $app->save();

        return $app->refresh();
    }

    public function delete(AppModel $app): void
    {
        $app->delete();
    }
}
