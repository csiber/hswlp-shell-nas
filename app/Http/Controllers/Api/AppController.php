<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\StoreAppRequest;
use App\Http\Requests\Apps\UpdateAppRequest;
use App\Http\Resources\AppResource as AppApiResource;
use App\Models\App as AppModel;
use App\Services\AppCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function __construct(private readonly AppCatalogService $catalogService)
    {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $apps = $this->catalogService->paginate($perPage);

        if ($request->boolean('with_instances')) {
            $apps->getCollection()->load('instances');
        }

        return AppApiResource::collection($apps);
    }

    public function store(StoreAppRequest $request): AppApiResource
    {
        $app = $this->catalogService->create($request->validated());

        return new AppApiResource($app);
    }

    public function show(AppModel $app): AppApiResource
    {
        return new AppApiResource($app->load('instances'));
    }

    public function update(UpdateAppRequest $request, AppModel $app): AppApiResource
    {
        $app = $this->catalogService->update($app, $request->validated());

        return new AppApiResource($app->load('instances'));
    }

    public function destroy(AppModel $app): JsonResponse
    {
        $this->catalogService->delete($app);

        return response()->json(null, 204);
    }
}
