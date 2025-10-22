<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Share extends Model
{
    use HasFactory;
    use UsesUuid;

    protected $fillable = [
        'name',
        'path',
        'smb_export',
        'nfs_export',
        'quota_gb',
        'owner_user_id',
    ];

    protected $casts = [
        'smb_export' => 'boolean',
        'nfs_export' => 'boolean',
        'quota_gb' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $share): void {
            if ($share->quota_gb === '') {
                $share->quota_gb = null;
            }
        });

        static::created(function (self $share): void {
            app(AuditLogService::class)->recordModelEvent('share.created', $share);
        });

        static::updated(function (self $share): void {
            app(AuditLogService::class)->recordModelEvent('share.updated', $share, $share->getChanges());
        });

        static::deleted(function (self $share): void {
            app(AuditLogService::class)->recordModelEvent('share.deleted', $share, ['id' => $share->getKey()]);
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(SharePermission::class);
    }
}
