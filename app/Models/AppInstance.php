<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppInstance extends Model
{
    use HasFactory;
    use UsesUuid;

    protected $fillable = [
        'app_id',
        'name',
        'bind_path',
        'env_json',
        'status',
    ];

    protected $casts = [
        'env_json' => 'array',
    ];

    protected static function booted(): void
    {
        static::created(function (self $instance): void {
            app(AuditLogService::class)->recordModelEvent('app-instance.created', $instance);
        });

        static::updated(function (self $instance): void {
            app(AuditLogService::class)->recordModelEvent('app-instance.updated', $instance, $instance->getChanges());
        });

        static::deleted(function (self $instance): void {
            app(AuditLogService::class)->recordModelEvent('app-instance.deleted', $instance, [
                'id' => $instance->getKey(),
                'app_id' => $instance->app_id,
            ]);
        });
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
}
