<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\TaskApiController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:api'])->group(function () {
    Route::get('/tasks/{idUser}', [TaskApiController::class, 'index']);
    Route::delete('/tasks/{id}', [TaskApiController::class, 'destroy']);
    Route::post('/tasks', [TaskApiController::class, 'store']);
    Route::get('/tasks/findOne/{id}', [TaskApiController::class, 'show']);
    Route::put('/tasks/{id}', [TaskApiController::class, 'update']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
});
