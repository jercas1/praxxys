<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\ProductApiController;

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

// ================================================================================================
// Authentication Controller
// ================================================================================================
Route::post(
    '/auth/login',
    [AuthApiController::class, 'login']
);

Route::middleware(['auth:sanctum'])->group(function () {
    // ================================================================================================
    // Authentication Controller
    // ================================================================================================
    Route::post(
        '/auth/logout',
        [AuthApiController::class, 'logout']
    );
    Route::get(
        '/auth/get-auth',
        [AuthApiController::class, 'getAuth']
    );

    // ================================================================================================
    // Product Controller
    // ================================================================================================
    Route::apiResource('/products', ProductApiController::class)->except([
        'update'
    ]);
    Route::post(
        '/products/validate/{step}',
        [ProductApiController::class, 'validateStepForm']
    );
    Route::post(
        '/products/{product}',
        [ProductApiController::class, 'update']
    );
});
