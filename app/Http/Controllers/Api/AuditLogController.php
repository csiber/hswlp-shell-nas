<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(private readonly AuditLogService $auditLogService)
    {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 25);
        $perPage = max(1, min(200, $perPage));

        $logs = $this->auditLogService->paginate($perPage);

        $logs->getCollection()->load('user');

        return AuditLogResource::collection($logs);
    }
}
