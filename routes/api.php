<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('products',ProductController::class);

Route::get('cart/session',[CartController::class,'index']);
Route::post('cart/add',[CartController::class,'addToCart']);
Route::get('/cart/{session_id}',[CartController::class,'getCart']);
