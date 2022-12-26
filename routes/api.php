<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    // Routes for Roles
    Route::apiResource('roles', RoleController::class);

    // Routes for Role-Permissions
    Route::apiResource('role-permissions', RolePermissionController::class)->only(['store', 'destroy']);
    Route::put('manage-role-permissions/{role_id}', [RolePermissionController::class, 'manage']);
});
