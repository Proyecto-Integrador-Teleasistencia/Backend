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

Route::post('login', [AuthController::class, 'login'])->middleware('api');
Route::post('register', [AuthController::class, 'register'])->middleware('api');

Route::apiResource('alerts', AlertsController::class)->middleware('api');
Route::apiResource('calls', CallsController::class)->middleware('api');
Route::apiResource('categories', CategoriesController::class)->middleware('api');
Route::apiResource('contacts', ContactsController::class)->middleware('api');
Route::apiResource('operators', OperatorsController::class)->middleware('api');
Route::apiResource('patients', PatientsController::class)->middleware('api');
Route::apiResource('subcategories', SubcategoriesController::class)->middleware('api');
Route::apiResource('zones', ZonesController::class)->middleware('api');
