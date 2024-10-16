<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/users',[UserController::class,'store']);
Route::post('/login',[UserController::class,'login']);
Route::post('/users/{user}/profileUpdate',[UserController::class,'profileImageUpdate'])->middleware('auth:sanctum');

//role
Route::post('/roles',[RoleController::class,'store']);
Route::delete('/roles/{role}',[RoleController::class,'delete']);

//categories
Route::get('/categories',[CategoryController::class,'show']);
Route::post('/categories',[CategoryController::class,'store'])->middleware('auth:sanctum',AdminMiddleware::class);
Route::put('/categories/{category}',[CategoryController::class,'update'])->middleware('auth:sanctum',AdminMiddleware::class);
Route::delete('/categories/{category}',[CategoryController::class,'delete'])->middleware('auth:sanctum',AdminMiddleware::class);

//products
Route::get('/products',[ProductController::class,'show']);
Route::get('/products/{product}',[ProductController::class,'detail']);
Route::post('/products',[ProductController::class,'store'])->middleware('auth:sanctum',AdminMiddleware::class);
Route::put('/products/{product}',[ProductController::class,'update'])->middleware('auth:sanctum',AdminMiddleware::class);
Route::delete('/products/{product}',[ProductController::class,'delete'])->middleware('auth:sanctum',AdminMiddleware::class);
Route::post('/products/{products}/update-image',[ProductController::class,'updateImage'])->middleware('auth:sanctum',AdminMiddleware::class);

//orders
Route::get('/orders',[OrderController::class,'show']);
Route::get('/orders/{product}',[OrderController::class,'detail']);
Route::post('/orders',[OrderController::class,'store'])->middleware('auth:sanctum',AdminMiddleware::class);
Route::put('/orders/{order}',[OrderController::class,'update'])->middleware('auth:sanctum',AdminMiddleware::class);
Route::delete('/orders/{order}',[OrderController::class,'delete'])->middleware('auth:sanctum',AdminMiddleware::class);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
