<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/rfq-form', function () {
    $products = Product::all();
    return view('rfq.create', compact('products'));
});
