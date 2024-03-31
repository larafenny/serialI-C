<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/device/create', [DeviceController::class, 'createNewDevice']);
Route::post('/device/{device_serial_number}/add-part', [DeviceController::class, 'addPartToDevice']);
Route::delete('/part/{part_serial_number}/remove-part-from-device', [PartController::class, 'removePartFromDevice']);
