<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RfqController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/rfq', [RfqController::class, 'store']);


Route::get('/products', [RfqController::class, 'products']);




Route::get('/rfq-form', function () {
    return view('rfq.create');
});