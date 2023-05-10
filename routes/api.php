<?php

use App\Http\Controllers\NodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/node/{parentId?}', [NodeController::class, 'index']);
    Route::post('/node/folder', [NodeController::class, 'createFolder']);
    Route::post('/node/file', [NodeController::class, 'uploadFile']);
    Route::patch('/node/move/', [NodeController::class, 'move']);
    Route::patch('/node/{id}', [NodeController::class, 'update']);
    Route::delete('/node/{id}', [NodeController::class, 'destroy']);
});
