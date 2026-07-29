<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Task Management System — REST API Routes
|--------------------------------------------------------------------------
|
| Base URL: /api
|
| Authentication:
|   POST /api/login   → returns a Bearer token
|   POST /api/logout  → revokes the current token  (requires token)
|   GET  /api/user    → returns authenticated user  (requires token)
|
| All routes below /api/projects and /api/tasks require the Bearer token
| to be passed in the Authorization header:
|   Authorization: Bearer <your-token>
|
*/

// ── Public Auth Endpoints ─────────────────────────────────────────────────────
Route::post('/login',  [AuthController::class, 'login'])->name('api.login');

// ── Protected Endpoints (Sanctum token required) ──────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Current user profile
    Route::get('/user',   [AuthController::class, 'user'])->name('api.user');
    Route::post('/logout',[AuthController::class, 'logout'])->name('api.logout');

    // Projects
    Route::get   ('/projects',          [ProjectController::class, 'index'])->name('api.projects.index');
    Route::post  ('/projects',          [ProjectController::class, 'store'])->name('api.projects.store');
    Route::get   ('/projects/{project}',[ProjectController::class, 'show'])->name('api.projects.show');
    Route::patch ('/projects/{project}',[ProjectController::class, 'update'])->name('api.projects.update');
    Route::delete('/projects/{project}',[ProjectController::class, 'destroy'])->name('api.projects.destroy');

    // Tasks (nested under projects for creation/listing)
    Route::get   ('/projects/{project}/tasks', [TaskController::class, 'index'])->name('api.projects.tasks.index');
    Route::post  ('/projects/{project}/tasks', [TaskController::class, 'store'])->name('api.projects.tasks.store');

    // Tasks (standalone for update/show/delete)
    Route::get   ('/tasks/{task}', [TaskController::class, 'show'])->name('api.tasks.show');
    Route::patch ('/tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('api.tasks.destroy');
});
