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
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\SubcategoriesController;
use App\Http\Controllers\Api\ZonesController;

Route::middleware('api')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // Rutas protegidas que requieren autenticación
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        
        // Patient Contacts
        Route::get('pacientes/{patient}/contactos', [ContactsController::class, 'getPatientContacts']);
        Route::post('pacientes/{patient}/contactos', [ContactsController::class, 'addPatientContact']);
        Route::put('contactos/{contact}', [ContactsController::class, 'update']);
        Route::delete('contactos/{contact}', [ContactsController::class, 'destroy']);
        
        // Patient Calls
        Route::get('pacientes/{patient}/llamadas', [CallsController::class, 'getPatientCalls']);
        
        // Zones
        Route::get('zonas', [ZonesController::class, 'index']);
        Route::get('zonas/{zona}', [ZonesController::class, 'show']);
        Route::get('zonas/{zona}/pacientes', [ZonesController::class, 'getZonePatients']);
        Route::get('zonas/{zona}/operadores', [ZonesController::class, 'getZoneOperators']);
        
        // Reports
        Route::get('reports/emergencias', [ReportsController::class, 'emergencies']);
        Route::get('reports/pacientes', [ReportsController::class, 'patients']);
        Route::get('reports/llamadas-programadas', [ReportsController::class, 'scheduledCalls']);
        Route::get('reports/llamadas-realizadas', [ReportsController::class, 'doneCalls']);
        Route::get('reports/historial-paciente/{patient}', [ReportsController::class, 'patientHistory']);

        // Users
        Route::get('usuarios', [OperatorsController::class, 'index']);
        Route::get('usuarios/{user}', [OperatorsController::class, 'show']);
        
        Route::apiResource('avisos', AlertsController::class);
        Route::apiResource('llamadas', CallsController::class);
        Route::apiResource('categorias', CategoriesController::class);
        Route::apiResource('contactos', ContactsController::class);
        Route::apiResource('operadores', OperatorsController::class);
        Route::apiResource('pacientes', PatientsController::class);
        Route::apiResource('subcategorias', SubcategoriesController::class);
        Route::apiResource('zonas', ZonesController::class);
    });
});
