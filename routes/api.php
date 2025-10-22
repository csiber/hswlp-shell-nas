<?php

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\AppInstanceController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\ShareController;
use App\Http\Controllers\Api\SharePermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('shares', ShareController::class);
    Route::apiResource('share-permissions', SharePermissionController::class);
    Route::apiResource('apps', AppController::class);
    Route::apiResource('app-instances', AppInstanceController::class);

    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});
