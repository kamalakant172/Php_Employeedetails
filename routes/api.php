<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AuthPostController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('employees', EmployeeController::class)->middleware('auth:api');
Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);

Route::apiResource('books', BookController::class)->middleware('auth:api');
Route::apiResource('ratings', RatingController::class)->middleware('auth:api');
// Route::post('books/{book}/ratings', [RatingController::class, 'store'])->middleware('auth:api');
Route::apiResource('products', ProductController::class)->middleware('auth:api');
Route::apiResource('categories', CategoryController::class)->middleware('auth:api');

