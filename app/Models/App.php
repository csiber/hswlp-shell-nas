<?php

namespace App\Models;

use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class App extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'status',
        'http_port',
    ];

    protected $casts = [
        'http_port' => 'integer',
    ];

    protected static function booted(): void
    {
        static::created(function (self $app): void {
            app(AuditLogService::class)->recordModelEvent('app.created', $app);
        });

        static::updated(function (self $app): void {
            app(AuditLogService::class)->recordModelEvent('app.updated', $app, $app->getChanges());
        });

        static::deleted(function (self $app): void {
            app(AuditLogService::class)->recordModelEvent('app.deleted', $app, ['id' => $app->getKey()]);
        });
    }

    public function instances(): HasMany
    {
        return $this->hasMany(AppInstance::class);
    }
}
