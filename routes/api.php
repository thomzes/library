<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\LoanController;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('book', BookController::class)->middleware('auth:api');
Route::apiResource('category', CategoryController::class)->middleware('auth:api');
Route::apiResource('loan', LoanController::class)->middleware('auth:api');

Route::get('borrowed-books', [LoanController::class, 'borrowedBooks'])->middleware("auth:api");
Route::patch('return-book/{loan}', [LoanController::class, 'returnBook'])->middleware("auth:api");


