<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SharePermissions\StoreSharePermissionRequest;
use App\Http\Requests\SharePermissions\UpdateSharePermissionRequest;
use App\Http\Resources\SharePermissionResource;
use App\Models\SharePermission;
use App\Services\SharePermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SharePermissionController extends Controller
{
    public function __construct(private readonly SharePermissionService $permissionService)
    {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $shareId = $request->query('share_id');

        $permissions = $this->permissionService->paginate($perPage, $shareId);

        return SharePermissionResource::collection($permissions);
    }

    public function store(StoreSharePermissionRequest $request): SharePermissionResource
    {
        $permission = $this->permissionService->create($request->validated());

        return new SharePermissionResource($permission->load(['share', 'user']));
    }

    public function show(SharePermission $sharePermission): SharePermissionResource
    {
        return new SharePermissionResource($sharePermission->load(['share', 'user']));
    }

    public function update(UpdateSharePermissionRequest $request, SharePermission $sharePermission): SharePermissionResource
    {
        $permission = $this->permissionService->update($sharePermission, $request->validated());

        return new SharePermissionResource($permission->load(['share', 'user']));
    }

    public function destroy(SharePermission $sharePermission): JsonResponse
    {
        $this->permissionService->delete($sharePermission);

        return response()->json(null, 204);
    }
}
