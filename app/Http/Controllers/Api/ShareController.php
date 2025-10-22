<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shares\StoreShareRequest;
use App\Http\Requests\Shares\UpdateShareRequest;
use App\Http\Resources\ShareResource as ShareApiResource;
use App\Models\Share;
use App\Services\ShareService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function __construct(private readonly ShareService $shareService)
    {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $shares = $this->shareService->paginate($perPage);

        $shares->getCollection()->load('owner');

        return ShareApiResource::collection($shares);
    }

    public function store(StoreShareRequest $request): ShareApiResource
    {
        $share = $this->shareService->create($request->validated());

        return new ShareApiResource($share->load(['owner', 'permissions.user']));
    }

    public function show(Share $share): ShareApiResource
    {
        return new ShareApiResource($share->load(['owner', 'permissions.user']));
    }

    public function update(UpdateShareRequest $request, Share $share): ShareApiResource
    {
        $share = $this->shareService->update($share, $request->validated());

        return new ShareApiResource($share->load(['owner', 'permissions.user']));
    }

    public function destroy(Share $share): JsonResponse
    {
        $this->shareService->delete($share);

        return response()->json(null, 204);
    }
}
