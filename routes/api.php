<?php

use Illuminate\Http\Response;
use Kapi\Traits\AppResponseTrait;
use Illuminate\Support\Facades\Route;
use Kapi\Http\Middleware\Authenticate;
use Kapi\Http\Controllers\TaskController;
use Kapi\Http\Controllers\UserController;
use Kapi\Http\Middleware\AdminAuthenticate;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Default Route - /
 */
Route::get('/', function () {
    return response()->json([
        'version' => '1.0.0',
        'message' => 'Welcome to KAPI Example REST API',
    ], Response::HTTP_OK);
});

/**
 * Route /users - will handle the user specific requests
 */
Route::post('users/register', [UserController::class, 'store']);
//Route::apiResource('users', "Kapi\Http\Controllers\UserController");
Route::apiResource('users', UserController::class)->middleware(AdminAuthenticate::class);

//tasks
Route::apiResource('tasks', TaskController::class)->middleware(Authenticate::class);

/**
 * Route Fallback - for any endpoints not supported by the system
 */
Route::fallback(function ($controller = '') {
    return (new AppResponseTrait())->sendErrorResponse(
        httpStatus: Response::HTTP_NOT_FOUND,
        errors: ['Please check the endpoint.'.($controller != '' ? ' Endpoint: '.url($controller).' not found' : '')]
    );
});
