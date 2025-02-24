<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Api\AlertsController;
use App\Http\Controllers\Api\CallsController;
use App\Http\Controllers\Api\CategoriasController;
use App\Http\Controllers\Api\ContactsController;
use App\Http\Controllers\Api\OperatorsController;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\SubcategoriasController;
use App\Http\Controllers\Api\ZonesController;
use App\Http\Controllers\Api\IncidentsController;

Route::middleware('api')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::prefix('auth/google')->group(function () {
        Route::get('redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
        Route::get('callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
    });

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

        // Paciente por zonas
        Route::get('pacientes/zonas/{zone}', [PatientsController::class, 'getPatientsByZones']);
        
        // Zones
        Route::get('zonas', [ZonesController::class, 'index']);
        Route::get('zonas/{zona}', [ZonesController::class, 'show']);
        Route::get('zonas/{zona}/pacientes', [ZonesController::class, 'getZonePatients']);
        Route::get('zonas/{zona}/operadores', [ZonesController::class, 'getZoneOperators']);
        
        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('check-emergencias', [ReportsController::class, 'checkEmergencies']);
            Route::get('informe-emergencias', [ReportsController::class, 'emergencyReport']);
            Route::get('pacientes/{id}', [ReportsController::class, 'patients']);
            Route::get('pacientes', [ReportsController::class, 'getAllInformes']);
            Route::get('llamadas-programadas', [ReportsController::class, 'scheduledCalls']);
            Route::get('llamadas-realizadas', [ReportsController::class, 'doneCalls']);
            Route::get('historial-paciente/{id}', [ReportsController::class, 'patientHistory']);
        });

        // Users
        Route::get('usuarios', [OperatorsController::class, 'index']);
        Route::get('usuarios/{user}', [OperatorsController::class, 'show']);
        
        Route::apiResource('avisos', AlertsController::class);
        Route::apiResource('llamadas', CallsController::class);
        Route::apiResource('categorias', CategoriasController::class);
        Route::apiResource('contactos', ContactsController::class);
        Route::apiResource('operadores', OperatorsController::class);
        Route::apiResource('pacientes', PatientsController::class);
        Route::apiResource('subcategorias', SubcategoriasController::class);
        Route::apiResource('zonas', ZonesController::class);
        Route::apiResource('incidencias', IncidentsController::class);
        Route::get('incidencias/paciente/{patient}', [IncidentsController::class, 'getPatientIncidents']);
        // Route::apiResource();

        // Llamadas del filtro
        Route::get('llamadas/teleoperador/{operator}', [CallsController::class, 'getOperatorCalls']);
        Route::get('llamadas/paciente/{patient}', [CallsController::class, 'getPatientCalls']);
        Route::get('llamadas/tipo/{type}', [CallsController::class, 'getCallsByType']);
        Route::get('llamadas/teleoperador/{operator}/paciente/{patient}', [CallsController::class, 'getCallsByOperatorPatient']);
        Route::get('llamadas/tipo/{type}/paciente/{patient}', [CallsController::class, 'getCallsByTypeAndPatient']);
        Route::get('llamadas/paciente/{patient}/tipo/{type}', [CallsController::class, 'getCallsByPatientAndType']);
        Route::get('llamadas/tipo/{type}/teleoperador/{operator}', [CallsController::class, 'getCallsByOperatorAndType']);
        Route::get('llamadas/tipo/{type}/teleoperador/{operator}/paciente/{patient}', [CallsController::class, 'getCallsByOperatorPatientAndType']);

        // Contactos por id del paciente
        Route::get('contactos/paciente/{patient}', [ContactsController::class, 'getPatientContacts']);

    });
});
