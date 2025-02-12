<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Rutas públicas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Rutas protegidas para la API
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('patients', 'App\Http\Controllers\Api\PatientsController');
    Route::apiResource('contacts', 'App\Http\Controllers\Api\ContactsController');
    Route::apiResource('operators', 'App\Http\Controllers\Api\OperatorsController');
    Route::apiResource('alerts', 'App\Http\Controllers\Api\AlertsController');
    Route::apiResource('calls', 'App\Http\Controllers\Api\CallsController');
    Route::apiResource('zones', 'App\Http\Controllers\Api\ZonesController');
});
