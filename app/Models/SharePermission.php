<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharePermission extends Model
{
    use HasFactory;
    use UsesUuid;

    public $timestamps = false;

    protected $fillable = [
        'share_id',
        'user_id',
        'perm',
    ];

    protected static function booted(): void
    {
        static::created(function (self $permission): void {
            app(AuditLogService::class)->recordModelEvent('share-permission.created', $permission);
        });

        static::updated(function (self $permission): void {
            app(AuditLogService::class)->recordModelEvent('share-permission.updated', $permission, $permission->getChanges());
        });

        static::deleted(function (self $permission): void {
            app(AuditLogService::class)->recordModelEvent('share-permission.deleted', $permission, [
                'id' => $permission->getKey(),
                'share_id' => $permission->share_id,
                'user_id' => $permission->user_id,
            ]);
        });
    }

    public function share(): BelongsTo
    {
        return $this->belongsTo(Share::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
