<?php

use App\Http\Controllers\Api\ActividadController;
use App\Http\Controllers\Api\ClienteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:60,1')->group(function () {
    Route::get('/cliente/{telefono}/actividades-pendientes', [ClienteController::class, 'actividadesPendientes'])
        ->middleware('throttle:10,1');
    
    Route::post('/actividad/confirmar', [ActividadController::class, 'confirmar'])
        ->middleware('throttle:5,1');
});
