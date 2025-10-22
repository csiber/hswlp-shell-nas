<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppInstances\StoreAppInstanceRequest;
use App\Http\Requests\AppInstances\UpdateAppInstanceRequest;
use App\Http\Resources\AppInstanceResource;
use App\Models\AppInstance;
use App\Services\AppInstanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppInstanceController extends Controller
{
    public function __construct(private readonly AppInstanceService $instanceService)
    {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $instances = $this->instanceService->paginate($perPage);
        $instances->getCollection()->load('app');

        return AppInstanceResource::collection($instances);
    }

    public function store(StoreAppInstanceRequest $request): AppInstanceResource
    {
        $instance = $this->instanceService->create($request->validated());

        return new AppInstanceResource($instance->load('app'));
    }

    public function show(AppInstance $appInstance): AppInstanceResource
    {
        return new AppInstanceResource($appInstance->load('app'));
    }

    public function update(UpdateAppInstanceRequest $request, AppInstance $appInstance): AppInstanceResource
    {
        $data = $request->validated();

        if (array_key_exists('name', $data)) {
            $exists = AppInstance::query()
                ->where('app_id', $appInstance->app_id)
                ->where('name', $data['name'])
                ->whereKeyNot($appInstance->getKey())
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'name' => __('Ezzel a névvel már létezik példány az adott alkalmazáshoz.'),
                ]);
            }
        }

        $instance = $this->instanceService->update($appInstance, $data);

        return new AppInstanceResource($instance->load('app'));
    }

    public function destroy(AppInstance $appInstance): JsonResponse
    {
        $this->instanceService->delete($appInstance);

        return response()->json(null, 204);
    }
}
