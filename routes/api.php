<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AlertsController;
use App\Http\Controllers\Api\CallsController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ContactsController;
use App\Http\Controllers\Api\OperatorsController;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\Api\SubcategoriesController;
use App\Http\Controllers\Api\ZonesController;

// Aplicar rate limiting a todas las rutas de la API
Route::middleware('throttle:api')->group(function () {
    // Rutas públicas de autenticación
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login')
        ->middleware('throttle:6,1'); // Limitar intentos de login

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register')
        ->middleware('throttle:6,1');

    // Rutas protegidas que requieren autenticación
    Route::middleware(['auth:sanctum'])->group(function () {
        // Rutas de autenticación
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);

        // Rutas para administradores
        Route::middleware(['role:admin'])->group(function () {
            Route::apiResource('operators', OperatorsController::class);
            Route::apiResource('zones', ZonesController::class);
            Route::apiResource('categories', CategoriesController::class);
            Route::apiResource('subcategories', SubcategoriesController::class);
        });

        // Rutas para operadores
        Route::middleware(['role:operator'])->group(function () {
            Route::apiResource('patients', PatientsController::class);
            Route::apiResource('contacts', ContactsController::class);
            Route::apiResource('calls', CallsController::class);
            Route::apiResource('alerts', AlertsController::class);
        });
    });
});
