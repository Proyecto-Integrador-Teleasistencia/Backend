<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Aplicar rate limiting a todas las rutas de la API
Route::middleware('throttle:api')->group(function () {
    // Rutas públicas de autenticación
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login')
        ->middleware('throttle:6,1'); // Limitar intentos de login

    // Rutas protegidas que requieren autenticación
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        // Rutas de autenticación
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);

        // Rutas para administradores
        Route::middleware('ability:*')->group(function () {
            Route::apiResource('operators', 'App\Http\Controllers\Api\OperatorsController');
            Route::apiResource('zones', 'App\Http\Controllers\Api\ZonesController');
        });

        // Rutas para operadores
        Route::middleware('ability:patients:view,patients:update')->group(function () {
            Route::apiResource('patients', 'App\Http\Controllers\Api\PatientsController');
            Route::apiResource('contacts', 'App\Http\Controllers\Api\ContactsController');
        });

        Route::middleware('ability:calls:create,calls:view,calls:update')->group(function () {
            Route::apiResource('calls', 'App\Http\Controllers\Api\CallsController');
        });

        Route::middleware('ability:alerts:view,alerts:update')->group(function () {
            Route::apiResource('alerts', 'App\Http\Controllers\Api\AlertsController');
        });
    });
});
