<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function record(string $action, array $payload = [], ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'payload_json' => empty($payload) ? null : $payload,
        ]);
    }

    public function paginate(int $perPage = 25): LengthAwarePaginator
    {
        return AuditLog::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function recordModelEvent(string $action, Model $model, array $context = []): AuditLog
    {
        $payload = array_merge([
            'model' => $model->getTable(),
            'model_class' => get_class($model),
            'model_id' => $model->getKey(),
        ], $context);

        if (! $context && $model->wasRecentlyCreated) {
            $payload['attributes'] = $this->extractAttributes($model);
        }

        return $this->record($action, $payload);
    }

    private function extractAttributes(Model $model): array
    {
        $attributes = $model->getAttributes();

        unset($attributes[$model->getKeyName()], $attributes['created_at'], $attributes['updated_at']);

        return $attributes;
    }
}
