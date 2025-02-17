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

Route::post('login', [AuthController::class, 'login'])->middleware('api');
Route::post('register', [AuthController::class, 'register'])->middleware('api');

// Rutas protegidas que requieren autenticación
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    
    // Patient Contacts
    Route::get('patients/{patient}/contacts', [ContactsController::class, 'getPatientContacts']);
    Route::post('patients/{patient}/contacts', [ContactsController::class, 'addPatientContact']);
    Route::put('contacts/{contact}', [ContactsController::class, 'update']);
    Route::delete('contacts/{contact}', [ContactsController::class, 'destroy']);
    
    // Patient Calls
    Route::get('patients/{patient}/calls', [CallsController::class, 'getPatientCalls']);
    
    // Zones
    Route::get('zones', [ZonesController::class, 'index']);
    Route::get('zones/{zone}', [ZonesController::class, 'show']);
    Route::get('zones/{zone}/patients', [ZonesController::class, 'getZonePatients']);
    Route::get('zones/{zone}/operators', [ZonesController::class, 'getZoneOperators']);
    
    // Reports
    Route::get('reports/emergencies', [ReportsController::class, 'emergencies']);
    Route::get('reports/patients', [ReportsController::class, 'patients']);
    Route::get('reports/scheduled-calls', [ReportsController::class, 'scheduledCalls']);
    Route::get('reports/done-calls', [ReportsController::class, 'doneCalls']);
    Route::get('reports/patient-history/{patient}', [ReportsController::class, 'patientHistory']);
    
    Route::apiResource('alerts', AlertsController::class);
    Route::apiResource('calls', CallsController::class);
    Route::apiResource('categories', CategoriesController::class);
    Route::apiResource('operators', OperatorsController::class);
    Route::apiResource('patients', PatientsController::class);
    Route::apiResource('subcategories', SubcategoriesController::class);
    Route::apiResource('zones', ZonesController::class);
});
